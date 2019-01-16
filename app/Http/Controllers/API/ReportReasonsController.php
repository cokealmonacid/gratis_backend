<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\ReportReasonRepository as ReportReasonRepository;

class ReportReasonsController extends ApiController
{
    /**
     * @var ReportReasonRepository
     */
    private $reportReasonRepository;

    /**
     * @param ReportReasonRepository $reportReasonRepository
     */
    function __construct(ReportReasonRepository $reportReasonRepository)
    {
        $this->reportReasonRepository  = $reportReasonRepository;
    }

    public function index(Request $request)
    {
    	$reasons = $this->reportReasonRepository->all();

    	return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $reasons]);
    }
}
