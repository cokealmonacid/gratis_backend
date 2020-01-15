<?php

namespace App\Http\Controllers\API;

use App\Repositories\UserRepository as UseRepo;
use Exception;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\ApiController;
use App\Http\Transformers\UserTransformer;
use Laravel\Passport\Bridge\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

use Auth;
use Validator;
use Socialite;


class UsersController extends ApiController
{
    /**
     * @var UserTransformer
     */
    protected $userTransformer;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserTransformer $userTransformer
     */
    function __construct(UserTransformer $userTransformer, UseRepo $userRepository)
    {
        $this->userTransformer = $userTransformer;
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        if (!$email or !$password) {
            return $this->respondFailedParametersValidation();
        }

            $user_rol = 'user';
            $user = $this->userRepository->findWithMailRol($email, $user_rol);

        try {
            $user_rol = 'user';
            $user = $this->userRepository->findWithMailRol($email, $user_rol);
            $attempt = Auth::attempt(['email' => $email, 'password' => $password]);

            if ($attempt) {

                return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->userTransformer->transform($user), 'client_token' => $this->setToken($user)]);
            }

            return $this->respondBadRequest(trans('messages.user_login_error'));
        } catch (Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function logout(Request $request)
    {

        $request->user('api')->token()->revoke();
        return $this->setStatusCode(Response::HTTP_OK)->respond(["message" => trans('messages.user_logout_success')]);

    }

    public function show(Request $request)
    {
        $user = $request->user('api');
        return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->userTransformer->transformUserDetail($user)]);
    }

    public function create(Request $request)
    {

        $validator = Validator::make(\Request::all(), User::rulesForCreate());

        if ($validator->fails()) {
            $error_message = $validator->errors()->first();
            return $this->respondFailedParametersValidation($error_message);
        }

        $_name  = $request->input('name');
        $_email = $request->input('email');
        $_password = $request->input('password');

        try {
            $user = $this->userRepository->addUser(['name' => $_name, 'email' => $_email, 'password' => bcrypt($_password)], 'user');
        } catch (Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }

        return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->userTransformer->transformUserDetail($user), 'client_token' => $this->setToken($user)]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(\Request::all(), User::rulesForUpdate());

        if ($validator->fails()) {
            $error_message = $validator->errors()->first();
            return $this->respondFailedParametersValidation($error_message);
        }
        $data_update = $request->only(['name', 'email', 'password', 'phone']);
        $user = $request->user('api');

        $this->userRepository->update($data_update, $user->id);

        return $this->setStatusCode(Response::HTTP_ACCEPTED)->respond(['data' => $this->userTransformer->transform($user)]);
    }

    public function updateAvatar(Request $request)
    {
        $validator = Validator::make(\Request::all(), User::rulesForAvatar());
        if ($validator->fails()) {
            return $this->respondFailedParametersValidation();
        }
        $user = $request->user('api');
        $avatar = $request->input('avatar');
        if (!$this->userRepository->update(['avatar' => $avatar], $user->id)) {
            return $this->respondInternalError();
        }

        return $this->setStatusCode(Response::HTTP_ACCEPTED)->respond(['data' => $this->userTransformer->transformUserDetail($user)]);
    }

    public function loginFacebook(Request $request)
    {

    }

    public function likePost(Request $request)
    {
        $validator = Validator::make(\Request::all(), ['post_id' => 'required']);
        if ($validator->fails()) {
            $error_message = $validator->errors()->first();
            return $this->respondFailedParametersValidation($error_message);
        }
        $user = $request->user('api');
        $post_id = $request->input('post_id');

        try {
            $_user_post_like = $this->userRepository->addUserLikePost($user->id, $post_id);
        } catch (Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }

        if ($_user_post_like == null)
            return $this->setStatusCode(Response::HTTP_OK)->respond([]);
        else
            return $this->setStatusCode(Response::HTTP_CREATED)->respond(['data' => $_user_post_like]);
    }

    private function setToken($user)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();
        return $_token_data = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ];
    }
}
