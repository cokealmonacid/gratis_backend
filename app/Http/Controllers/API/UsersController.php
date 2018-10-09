<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\ApiController;
use App\Http\Transformers\UserTransformer;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\User_Rol;
use Auth;
use Validator;
use Illuminate\Support\Str;


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
    	$email    = $request->input('email');
    	$password = $request->input('password');

    	if (!$email or !$password) {
    		return $this->respondFailedParametersValidation();
    	}

    	$user     = User::where('email', $email)->first();
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

            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            $token->save();
            $_token_data =  [
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                ->toDateTimeString() ];

    		return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->userTransformer->transform($user), 'client_token' =>$_token_data ]);
    	}

    	return $this->respondBadRequest('The email and password dont match');
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->setStatusCode(Response::HTTP_OK)->respond(["message" => "Se ha desconectado con exito."]);

    }

    public function create(Request $request){

        $validator = Validator::make(\Request::all(), User::rulesForCreate()->rules , User::rulesForCreate()->messages);

        if ($validator->fails()) {
            $error_message = $validator->errors()->first();
            return $this->respondFailedParametersValidation($error_message);
        }

        $_email    = $request->input('email');
        $_password = $request->input('password');

        $user = User::create(
            [
            'email'     => $_email,
            'password'  => bcrypt($_password)
            ]
        );

         return $this->setStatusCode(Response::HTTP_CREATED)->respond(['data' => $this->userTransformer->transform($user)]);
    }


}
