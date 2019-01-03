<?php

namespace App\Repositories;

use App\Models\Provincia;

class ProvinciaRepository implements RepositoryInterface
{
	protected $provincia_model;

	public function __construct(Provincia $provincia)
	{
		$this->provincia_model = $provincia;
	}

	public function all()
	{
		return $this->provincia_model->all();
	}	

	public function create(array $data)
	{
		return $this->provincia_model->create($data);
	}

	public function update($id, array $data)
	{
		return $this->provincia_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->provincia_model->destroy($id);
	}

	public function find($id)
	{
		return $this->provincia_model->find($id);
	}
}