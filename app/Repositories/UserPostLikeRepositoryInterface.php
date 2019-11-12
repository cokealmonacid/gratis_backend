<?php

namespace App\Repositories;

interface UserPostLikeRepositoryInterface extends RepositoryInterface
{
	public function findPost($user_id, $post_id);
}