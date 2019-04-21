<?php

namespace App\Http\Controllers\API;

use App\Repositories\UserRepository as UserRepository;
use App\Repositories\PasswordResetRepository as PasswordResetRepository;
use Exception;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Validator;

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
        	return $this->respondFailedParametersValidation(trans('passwords.user'));
        }

        $token = str_random(60);
        $passwordReset = $this->passwordResetRepository->findFirstWithAttribute('email', $request->email);
        if ($passwordReset) {
        	$passwordReset = $this->passwordResetRepository->update(['token' => $token], $passwordReset->id);
        } else {
        	$passwordReset = $this->passwordResetRepository->create([
        		'email' => $request->email,
        		'token' => $token
        	]);
        }

        try {
        	$user->notify(new PasswordResetRequest($token));
        	return $this->setStatusCode(Response::HTTP_OK)->respond(["message" => trans('passwords.sent')]);

        } catch (Exception $e) {
        	return $this->respondBadRequest();
        }
    }

    public function find($token)
    {
    	$passwordReset = $this->passwordResetRepository->findFirstWithAttribute('token', $token);
        if (!$passwordReset) {
        	return $this->respondFailedParametersValidation(trans('passwords.token'));
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $this->PasswordResetRepository->delete($passwordReset->id);
            return $this->respondFailedParametersValidation(trans('passwords.token'));
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

        $user = $this->userRepository->findFirstWithAtribute('email', $request->email);
        if (!$user) {
            return $this->respondFailedParametersValidation(trans('passwords.user'));
        }

        $passwordReset = $this->passwordResetRepository->findWithAttributes([
            'token'  => $request->token,
            'email' => $request->email
        ]);

        if (!$passwordReset) {
        	return $this->respondFailedParametersValidation(trans('passwords.user'));
        }

        try {
        	$this->userRepository->update(['password' => $request->password], $user->id);

        	$user->notify(new PasswordResetSuccess($passwordReset));
        	return $this->setStatusCode(Response::HTTP_CREATED)->respond(['data' => $this->userTransformer->transform($user)]);

        } catch (Exception $e) {
        	return $this->respondBadRequest();
        }
    }
}
