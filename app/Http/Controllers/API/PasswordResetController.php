<?php

namespace App\Http\Controllers\API;

use App\Repositories\UserRepository as UserRepository;
use App\Repositories\PasswordResetRepository as PasswordResetRepository;
use Exception;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;

class PasswordResetController extends ApiController
{
	/**
	* @var userRepository
	*/
	private $userRepository;

	/**
	* @param UserRepository $userRepository
	**/
	function __construct(UserRepository $userRepository, PasswordResetRepository $passwordResetRepository)
	{
		$this->userRepository = $userRepository;
		$this->passwordResetRepository = $passwordResetRepository;
	}

    public function create(Request $request) 
    {
    	$validator = Validator::make(\Request::all(), PasswordReset::rules());
        if ($validator->fails()) {
            $error_message = $validator->errors()->first();
            return $this->respondFailedParametersValidation($error_message);
        }

        $user = $this->userRepository->findFirstWithAtribute('email', $request->email);
        if (!$user) {
        	return $this->respondFailedParametersValidation('The email provided doesnt exist');
        }

        $passwordReset = $this->PasswordResetRepository->findFirstWithAtribute('email', $request->email);
        if ($passwordReset) {
        	$passwordReset = $this->passwordResetRepository->update(['token' => str_random(60)], $passwordReset->id);
        } else {
        	$passwordReset = $this->passwordResetRepository->create([
        		'email' => $request->email,
        		'token' => str_random(60)
        	]);
        }

        try {
        	$user->notify(new PasswordResetRequest($passwordReset->token));
        	return $this->setStatusCode(Response::HTTP_OK)->respond(["message" => "We have e-mailed your password reset link!"]);

        } catch (Exception $e) {
        	return $this->respondBadRequest($e->getMessage());
        }
    }

    public function find($token)
    {
    	$passwordReset = $this->PasswordResetRepository->findFirstWithAttribute('token', $token);
        if (!$passwordReset) {
        	return $this->respondFailedParametersValidation('This password reset token is invalid.');
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $this->PasswordResetRepository->delete($passwordReset->id);
            return $this->respondFailedParametersValidation('This password reset token is invalid.');
        }

        return $this->setStatusCode(Response::HTTP_ACCEPTED)->respond(['data' => $passwordReset]);
    }

    public function reset(Request $request)
    {
    	$validator = Validator::make(\Request::all(), PasswordReset::rulesForResetPass());
        if ($validator->fails()) {
            $error_message = $validator->errors()->first();
            return $this->respondFailedParametersValidation($error_message);
        }

        $passwordReset = $this->PasswordResetRepository->findWithAttributes([
            'token'  => $request->token,
            'email' => $request->email
        ]);

        if (!$passwordReset) {
        	return $this->respondFailedParametersValidation('This password reset token is invalid.');
        }

        $user = $this->userRepository->findFirstWithAtribute('email', $passwordReset->email);
        if (!$user) {
        	return $this->respondFailedParametersValidation('The email provided doesnt exist');
        }

        try {
        	$this->userRepository->update(['password' => $request->password], $user->id);

        	$user->notify(new PasswordResetSuccess($passwordReset));
        	return $this->setStatusCode(Response::HTTP_SUCCESS)->respond(['data' => $this->userTransformer->transform($user)]);

        } catch (Exception $e) {
        	return $this->respondBadRequest($e->getMessage());
        }
    }
}
