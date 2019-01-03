<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository implements RepositoryInterface
{
	protected $tag_model;

	public function __construct(Tag $tag_model)
	{
		$this->tag_model = $tag_model;
	}

	public function all()
	{
		return $this->tag_model->all();
	}	

	public function create(array $data)
	{
		return $this->tag_model->create($data);
	}

	public function update($id, array $data)
	{
		return $this->tag_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->tag_model->destroy($id);
	}

	public function find($id)
	{
		return $this->tag_model->whereId($id)->first();
	}
}