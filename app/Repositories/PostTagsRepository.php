<?php

namespace App\Repositories;

use App\Models\Post_Tags;

class PostTagsRepository implements RepositoryInterface
{
	protected $post_tags_model;

	public function __construct(Post_Tags $post_tags)
	{
		$this->post_tags_model = $post_tags;
	}

	public function all()
	{
		return $this->post_tags_model->all();
	}	


	public function create(array $data)
	{
		return $this->post_tags_model->create($data);
	}

	public function update($id, array $data)
	{
		return $this->post_tags_model->whereId($id)->update($data);
	}

	public function delete($post_id)
	{
		return $this->post_tags_model->where('post_id', $post_id)->delete();
	}

	public function find($id)
	{
		return $this->$post_tags_model->find($id);
	}
}