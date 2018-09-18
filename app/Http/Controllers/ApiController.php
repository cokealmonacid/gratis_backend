<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
	/**
	 * @var int
     */
	protected $statusCode = 200;

	/**
	 * @return mixed
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * @param mixed $statusCode
	 * @return $this
	 */
	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 * @param $data
	 * @param array $headers
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
	public function respond($data, $headers = [])
	{
		return response($data, $this->getStatusCode(), $headers);
	}

	/**
	 * @param $message
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
	public function respondWithError($message)
	{
		return $this->respond([
			'error' => [
				'message' => $message,
				'status_code' => $this->getStatusCode()
			]
		]);
	}

	/**
	 * @param $message
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
	 */
	public function responseCreated($message)
	{
		return $this->setStatusCode(Response::HTTP_CREATED)
			->respond([
			'message' => $message
		]);
	}

	/**
	 * @param string $message
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
	 */
	public function responseOK($message = 'success')
	{
		return $this->setStatusCode(Response::HTTP_OK)
			->respond([
			'message' => $message
		]);
	}

	/**
	 * @param string $message
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
	public function respondBadRequest($message = 'The request cannot be fulfilled due to bad syntax')
	{
		return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
			->respondWithError($message);
	}

	/**
	 * @param string $message
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
	 */
	public function respondFailedParametersValidation($message = 'Paramaters failed validation for a user.')
	{
		return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
			->respondWithError($message);
	}

	/**
	 * @param string $message
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
	public function respondNotFound($message = 'Not Found!')
	{
		return $this->setStatusCode(Response::HTTP_NOT_FOUND)
			->respondWithError($message);
	}

	/**
	 * @param string $message
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
	public function respondInternalError($message = 'Internal Error!')
	{
		return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
			->respondWithError($message);
	}
}
