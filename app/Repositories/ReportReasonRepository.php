<?php

namespace App\Repositories;

use App\Models\Report_Reason;

class ReportReasonRepository implements RepositoryInterface
{
	protected $report_reason_model;

	public function __construct(Report_Reason $report_reason)
	{
		$this->report_reason_model = $report_reason;
	}

	public function all()
	{
		return $this->report_reason_model->all();
	}	

	public function create(array $data)
	{
		return $this->report_reason_model->create($data);
	}

	public function update(array $data, $id)
	{
		return $this->report_reason_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->report_reason_model->destroy($id);
	}

	public function find($id)
	{
		return $this->report_reason_model->find($id);
	}
}