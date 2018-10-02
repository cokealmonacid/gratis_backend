<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Post;
use Auth;

class PostsController  extends ApiController
{

    public function getPublicPosts (Request $request) {
        $_page    = $request->input('page');
        if (!is_null($_page) && !is_numeric($_page) ) {
            return $this->respondFailedParametersValidation();

        }

        $_posts = Post::where('state_id','=',1)
            ->groupBy('posts.id')
            ->join('photos', 'photos.post_id', '=', 'posts.id')
            ->paginate('5',['posts.id as id','posts.title as title','posts.description as description', 'photos.thumbnail as thumbnail'],'page',$_page);

        return $_posts;

    }

}