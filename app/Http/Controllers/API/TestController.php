<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class TestController extends ApiController
{
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function test(Request $request) {

		$users = User::all();

		return $users;
	}
}
