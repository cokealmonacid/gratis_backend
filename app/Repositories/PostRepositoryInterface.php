<?php

namespace App\Repositories;

interface PostRepositoryInterface extends RepositoryInterface
{
	public function showPosts(object $data);
}