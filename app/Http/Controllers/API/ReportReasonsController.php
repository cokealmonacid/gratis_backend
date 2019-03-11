<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\ReportReasonRepository as ReportReasonRepository;
use App\Repositories\PostRepository as PostRepository;
use App\Models\Report_Reason;
use Validator;
use Auth;

class ReportReasonsController extends ApiController
{
    /**
     * @var ReportReasonRepository
     */
    private $reportReasonRepository;

    /**
     * @param ReportReasonRepository $reportReasonRepository
     */
    function __construct(ReportReasonRepository $reportReasonRepository, PostRepository $postRepository)
    {
        $this->reportReasonRepository  = $reportReasonRepository;
        $this->postRepository          = $postRepository;
    }

    public function index(Request $request)
    {
    	$reasons = $this->reportReasonRepository->all();

    	return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $reasons]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Report_Reason::rules());
        if ($validator->fails()) {
            return $this->respondFailedParametersValidation($validator->errors()->first());
        }

        $post_id = $request->post_id;
        $post    = $this->postRepository->find($post_id);
        if (!$post) {
            return $this->respondBadRequest('This post does not exist');
        }

        $report_id = $request->report_id;
        $report    = $this->reportReasonRepository->find($report_id);
        if (!$report) {
            return $this->respondBadRequest('This report does not exist');
        }

        try {
            $user = $request->user('api');
            $report = $this->reportReasonRepository->createReportPost($post_id, $report_id, $user->id);

        } catch (Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }

        return $this->setStatusCode(Response::HTTP_CREATED)->respond(['data' => $report]);
    }
}
