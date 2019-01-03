<?php

namespace App\Repositories;

use App\Models\User_Post_Like;

class UserPostLikeRepository implements RepositoryInterface
{
	protected $user_post_like_model;

	public function __construct(User_Post_Like $user_post_like)
	{
		$this->user_post_like_model = $user_post_like;
	}

	public function all()
	{
		return $this->user_post_like_model->all();
	}	

	public function create(array $data)
	{
		return $this->user_post_like_model->create($data);
	}

	public function update($id, array $data)
	{
		return $this->user_post_like_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->user_post_like_model->destroy($id);
	}

	public function find($id)
	{
		return $this->user_post_like_model->where('user_id', $id)->pluck('post_id');
	}
}