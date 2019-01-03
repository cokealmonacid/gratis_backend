<?php

namespace App\Repositories;

interface PhotoRepositoryInterface extends RepositoryInterface
{
	public function select($post_id);
}