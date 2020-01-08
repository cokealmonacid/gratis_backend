<?php

namespace App\Http\Controllers\API;

namespace App\Http\Controllers\API;

use App\Repositories\TagRepository as TagRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tag;

class TagsController extends ApiController
{
    function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function index(Request $request)
    {
    	$_tags = $this->tagRepository->all();

    	return $this->setStatusCode(Response::HTTP_OK)->respond($_tags);
    }
}
