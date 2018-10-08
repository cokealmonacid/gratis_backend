<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;

class TestController extends ApiController
{
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function test() {

		return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => 'esta es una prueba']);
	}
}
