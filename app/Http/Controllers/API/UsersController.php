<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Transformers\UserTransformer;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\User_Rol;
use Auth;

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
    		return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->userTransformer->transform($user)]);
    	}

    	return $this->respondBadRequest('The email and password dont match');
    }
}
