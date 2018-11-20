<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\ApiController;
use App\Http\Transformers\UserTransformer;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\User_Rol;
use App\Models\User_Post_Like;
use App\Models\Rol;
use Auth;
use Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Socialite;


class UsersController extends ApiController
{
    /**
     * @var UserTransformer
     */
    protected $userTransformer;

    /**
     * @param UserTransformer $userTransformer
     */
    function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        if (!$email or !$password) {
            return $this->respondFailedParametersValidation();
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->respondBadRequest('This email account does not exist');
        }

        $match_these = ['user_id' => $user->id, 'rol_id' => 1];
        $hasRol = User_Rol::where($match_these)->first();
        if (!$hasRol) {
            return $this->respondBadRequest('This email account does not exist');
        }
        $attempt = Auth::attempt(['email' => $email, 'password' => $password]);

        if ($attempt) {

            return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->userTransformer->transform($user), 'client_token' => $this->setToken($user)]);
        }

        return $this->respondBadRequest('The email and password dont match');
    }

    public function logout(Request $request)
    {

        $request->user('api')->token()->revoke();
        return $this->setStatusCode(Response::HTTP_OK)->respond(["message" => "Logout success."]);

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

        $_email = $request->input('email');
        $_password = $request->input('password');

        $user = User::create(
            [
                'email' => $_email,
                'password' => bcrypt($_password)
            ]
        );

        $rol = Rol::where('description', 'user')->first();
        User_rol::create([
            'user_id' => $user->id,
            'rol_id' => $rol->id
        ]);

        return $this->setStatusCode(Response::HTTP_CREATED)->respond(['data' => $this->userTransformer->transform($user)]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(\Request::all(), User::rulesForUpdate());

        if ($validator->fails()) {
            $error_message = $validator->errors()->first();
            return $this->respondFailedParametersValidation($error_message);
        }
        $user = $request->user('api');

        $data_update = $request->only(['name', 'email', 'password', 'phone']);
        if (isset($data_update['password'])) {
            $data_update['password'] = bcrypt($data_update['password']);
        }
        $user->update(
            $data_update
        );
        return $this->setStatusCode(Response::HTTP_ACCEPTED)->respond(['data' => $this->userTransformer->transform($user)]);
    }

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $providerUser = Socialite::driver('facebook')->stateless()->user();
        } catch (Exception $e) {
            return $this->respondInternalError();
        }

        $user = User::where('email', $providerUser->getEmail())->first();
        if ($user) {
            $user->provider_id = $providerUser->getId();
            $user->save();

            return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->userTransformer->transform($user), 'client_token' => $this->setToken($user)]);
        }

        $user = User::where('provider_id', $providerUser->getId())->first();
        if ($user) {
            return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->userTransformer->transform($user), 'client_token' => $this->setToken($user)]);
        }

        $user = User::create([
            'name' => $providerUser->getName(),
            'email' => $providerUser->getEmail(),
            'provider_id' => $providerUser->getId()
        ]);

        if (!$user) {
            return $this->respondInternalError();
        }

        return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->userTransformer->transform($user), 'client_token' => $this->setToken($user)]);
    }

    public function likePost(Request $request)
    {
        $validator = Validator::make(\Request::all(), ['post_id' => 'required']);
        if ($validator->fails()) {
            $error_message = $validator->errors()->first();
            return $this->respondFailedParametersValidation($error_message);
        }

        $_post_id = $request->input('post_id');
        $_post = Post::where('id', $_post_id)->first();
        if (!$_post) {
            return $this->respondBadRequest('Post not exist');
        }

        $user = $request->user('api');

        $user_like_post = User_Post_Like::where('post_id', $_post->id)->where('user_id',$user->id)->first();

        if ($user_like_post){
            $user_like_post->delete();
            return $this->setStatusCode(Response::HTTP_OK)->respond([]);
        }

        $_user_post_like = User_Post_Like::create(
            [
                'user_id' => $user->id,
                'post_id' => $_post->id
            ]
        );

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
