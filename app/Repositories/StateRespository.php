<?php

namespace App\Repositories;

use App\Models\State;

class StateRespository implements RepositoryInterface
{
	protected $state_model;

	public function __construct(State $state_model)
	{
		$this->state_model = $state_model;
	}

	public function all()
	{
		return $this->state_model->all();
	}	

	public function create(array $data)
	{
		return $this->state_model->create($data);
	}

	public function update(array $data, $id)
	{
		return $this->state_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->state_model->destroy($id);
	}

	public function find($id)
	{
		return $this->state_model->whereId($id)->first();
	}
}