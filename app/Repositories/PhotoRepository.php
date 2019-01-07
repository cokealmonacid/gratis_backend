<?php

namespace App\Repositories;

use App\Models\Photo;

class PhotoRepository implements PhotoRepositoryInterface
{
	protected $photo_model;

	public function __construct(Photo $photo)
	{
		$this->photo_model = $photo;
	}

	public function all()
	{
		return $this->photo_model->all();
	}	

	public function create(array $data)
	{
		return $this->photo_model->create($data);
	}

	public function update(array $data, $id)
	{
		return $this->photo_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->photo_model->where('post_id', $id)->delete();
	}

	public function find($id)
	{
		return $this->photo_model->find($id);
	}

	public function select($post_id)
	{
		return $this->photo_model->where('post_id', $post_id)->get();
	}
}