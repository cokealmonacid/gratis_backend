<?php

namespace App\Repositories;

interface PostRepositoryInterface extends RepositoryInterface
{
	public function show(object $data);

	public function showDetail($id);
}