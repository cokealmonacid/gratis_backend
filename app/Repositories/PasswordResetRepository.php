<?php

namespace App\Repositories;

use App\Models\PasswordReset;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
	protected $pass_reset_model;

	public function __construct(PasswordReset $password_reset)
	{
		$this->pass_reset_model = $password_reset;
	}

	public function all()
	{
		return $this->pass_reset_model->all();
	}	

	public function create(array $data)
	{
		return $this->pass_reset_model->create($data);
	}

	public function update(array $data, $id)
	{
		return $this->pass_reset_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->pass_reset_model->where('id', $id)->delete();
	}

	public function find($id)
	{
		return $this->pass_reset_model->find($id);
	}

	public function findFirstWithAttribute(String $atribute, $value)
	{
        return $this->pass_reset_model->where($atribute, $value)->get()->first();
	}

	public function findWithAttributes(array $data)
	{
        return $this->pass_reset_model->where($data)->first();
	}
}