<?php

namespace App\Repositories;

interface PostRepositoryInterface extends RepositoryInterface
{
	public function createWithPostAndTags($photos, $tags, array $data);

	public function show(object $data);

	public function showDetail($id);

	public function updateWithPostAndTags($id, $photos, $tags, array $data);
}