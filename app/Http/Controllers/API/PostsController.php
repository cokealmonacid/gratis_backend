<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Transformers\PostTransformer;
use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use App\Models\Post_Tags;
use App\Models\Provincia;
use App\Models\Tag;
use Validator;
use Auth;

class PostsController  extends ApiController
{

    /**
     * @var PostTransformer
     */
    protected $postTransformer;

    /**
     * @param PostTransformer $postTransformer
     */
    function __construct(PostTransformer $postTransformer)
    {
        $this->postTransformer = $postTransformer;
    }

    public function index (Request $request) {
        $_page    = $request->input('page');
        if (!is_null($_page) && !is_numeric($_page) ) {
            return $this->respondFailedParametersValidation();
        }

        $_posts = Post::where('state_id','=',1)
            ->groupBy('posts.id')
            ->join('photos', 'photos.post_id', '=', 'posts.id')
            ->paginate('5',['posts.id as id','posts.title as title','posts.description as description', 'photos.thumbnail as thumbnail'],'page',$_page);

        return $this->setStatusCode(Response::HTTP_OK)->respond($_posts);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), Post::rules());
        if ($validator->fails()) {
            return $this->respondFailedParametersValidation($validator->errors()->first());
        }

        $tags = $this->check_tags($request->input('tags'));
        if (!$tags) {
            return $this->respondFailedParametersValidation('The tags provided doesnt exist');
        }

        $photos = $this->check_photos($request->input('photos'));
        if (!$photos) {
            return $this->respondFailedParametersValidation('There is a problem with the images provided');
        }

        $title        = $request->input('title');
        $description  = $request->input('description');
        $provincia_id = $request->input('provincia_id');
        $provincia    = Provincia::whereId($provincia_id)->first();
        if (!$provincia) {
            return $this->respondFailedParametersValidation('Paramaters failed validation for a post');
        }

        $user = Auth::guard('api')->user();
        $post = Post::create([
            'title'        => $title,
            'description'  => $description,
            'provincia_id' => $provincia->id,
            'user_id'      => $user->id,
            'state_id'     => 2
        ]);

        if (!$post) {
            return $this->respondFailedParametersValidation('Error while internally saving an post');
        }

        foreach($tags as $tag) {
            Post_Tags::create([
                'post_id' => $post->id,
                'tag_id'  => $tag
            ]);
        }

        foreach($photos as $photo) {
            Photo::create([
                'post_id'   => $post->id,
                'image'     => $photo['content'],
                'thumbnail' => Photo::createThumbnail($photo['content']),
                'extension' => $photo['extension'],
                'principal' => $photo['principal']
            ]);
        }

        return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->postTransformer->transform($post)]);
    }

    public function getPost ($id , Request $request) {
        if ($this->check_post_id($id)) {
            $post_detail = Post::getDetailPost($id);
            $post_photos = Photo::where('post_id' ,'=', $id)->get();
            if ( $request->user('api') ) {
                return $this
                    ->setStatusCode(Response::HTTP_OK)
                    ->respond(['data' => $this->postTransformer->transformPostDetailUsers($post_detail,$post_photos)]);

            }
            else {
                return $this
                    ->setStatusCode(Response::HTTP_OK)
                    ->respond(['data' =>
                        $this->postTransformer->transformPostDetailPublic($post_detail,$post_photos)

                    ]);
            }
        }
        else{
            return $this->respondBadRequest('Post not found');

        }


    }
    private function check_post_id($post_id){
        $post = Post::whereId($post_id)->first();
        return !is_null($post);

    }

    private function check_tags($tags){
        foreach($tags as $tag) {
            $tag = Tag::whereId($tag)->first();
            if (!$tag) {
                return false;
            }
        }

        return $tags;
    }

    private function check_photos($photos){
        
        foreach($photos as $photo){

            if (!is_array($photo)) {
                return false;
            }

            $validator = Validator::make($photo, Photo::rules());
            if ($validator->fails()) {
                return false;
            }
        }

        return $photos;
    }


}