<?php

namespace App\Http\Controllers\API;

use App\Repositories\RegionRepository as RegionRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Post;

class RegionsController extends ApiController
{
    function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function index(Request $request)
    {
    	$_regions = $this->regionRepository->all();

    	return $this->setStatusCode(Response::HTTP_OK)->respond($_regions);
    }
}
