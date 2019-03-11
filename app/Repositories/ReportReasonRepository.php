<?php

namespace App\Repositories;

use App\Models\Report_Reason;
use App\Models\Post_Report;

class ReportReasonRepository implements RepositoryInterface
{
	protected $report_reason_model;
	protected $post_report_model;

	public function __construct(Report_Reason $report_reason, Post_Report $post_report)
	{
		$this->report_reason_model = $report_reason;
		$this->post_report_model   = $post_report;
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

	public function createReportPost($post_id, $report_id, $user_id)
	{
		return $this->post_report_model->create([
          'post_id'   => $post_id,
          'report_id' => $report_id,
          'user_id'   => $user_id
		]);
	}
}