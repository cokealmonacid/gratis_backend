<?php

namespace App\Repositories;

use App\Models\Region;

class RegionRepository implements RepositoryInterface
{
	protected $region_model;

	public function __construct(Region $region_model)
	{
		$this->region_model = $region_model;
	}

	public function all()
	{
		return $this->region_model->all();
	}	

	public function create(array $data)
	{
		return $this->region_model->create($data);
	}

	public function update(array $data, $id)
	{
		return $this->region_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->region_model->destroy($id);
	}

	public function find($id)
	{
		return $this->region_model->whereId($id)->first();
	}
}