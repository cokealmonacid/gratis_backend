<?php

namespace App\Http\Controllers\API;

use App\Repositories\PostRepository as PostRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Transformers\PostTransformer;
use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use App\Models\User_Post_Like;
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
    function __construct(PostTransformer $postTransformer, PostRepository $postRepository)
    {
        $this->postTransformer = $postTransformer;
        $this->postRepository  = $postRepository;
    }

    public function showPosts(Request $request) {

        $validator = Validator::make($request->all(), Post::rulesFilter());

        if ($validator->fails()) {
            return $this->respondFailedParametersValidation($validator->errors()->first());
        }

        return $this->setStatusCode(Response::HTTP_OK)->respond($_posts);
    }

    public function store(Request $request) 
    {
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

        $provincia_id = $request->input('provincia_id');
        $provincia    = Provincia::whereId($provincia_id)->first();
        if (!$provincia) {
            return $this->respondFailedParametersValidation('Paramaters failed validation for a post');
        }

        $user = Auth::guard('api')->user();
        $post = Post::create([
            'title'        => $request->input('title'),
            'description'  => $request->input('description'),
            'provincia_id' => $provincia->id,
            'user_id'      => $user->id,
            'state_id'     => 2
        ]);

        if (!$post) {
            return $this->respondFailedParametersValidation('Error while internally saving an post');
        }

        $this->post_tags($post->id, $tags);

        $this->post_photos($post->id, $photos);

        return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->postTransformer->transform($post)]);
    }

    public function update($id, Request $request)
    {
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

        $provincia_id = $request->input('provincia_id');
        $provincia    = Provincia::whereId($provincia_id)->first();
        if (!$provincia) {
            return $this->respondFailedParametersValidation('Paramaters failed validation for a post');
        }

        $post = Post::whereId($id)->first();
        if (!$post) {
            return $this->respondBadRequest('This post does not exist');
        }

        $user = Auth::guard('api')->user();
        if ($post->user_id != $user->id) {
            return $this->respondForbidden();
        }

        $post->title        = $request->input('title');
        $post->description  = $request->input('description');
        $post->provincia_id = $provincia->id;
        $post->save();

        $tags_delete = Post_Tags::where('post_id', $post->id)->delete();
        if ($tags_delete) {
            $this->post_tags($post->id, $tags);
        }

        $photos_delete = Photo::where('post_id', $post->id)->delete();
        if ($photos_delete) {
            $this->post_photos($post->id, $photos);
        }

        return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->postTransformer->transform($post)]);
    }

    public function show($id , Request $request) 
    {
        if ($this->check_post_id($id)) {

            $post_detail = Post::whereId($id)
                ->first()
                ->join('users','users.id', '=' ,'posts.user_id')
                ->select(
                    'users.id as user_id'
                    ,'users.name as user_name'
                    ,'users.phone as user_phone'
                    ,'users.avatar as user_avatar'
                    ,'users.email as user_email'
                    ,'posts.id as post_id'
                    ,'posts.title as post_title'
                    ,'posts.description as post_description'

                )
                ->first();


            $post_photos = Photo::where('post_id' ,'=', $id)->get();
            if ( $request->user('api') ) {
                return $this
                    ->setStatusCode(Response::HTTP_OK)
                    ->respond(['data' => $this->postTransformer->transformPostDetailUsers($post_detail,$post_photos)]);

            } else {
                return $this
                    ->setStatusCode(Response::HTTP_OK)
                    ->respond(['data' =>
                        $this->postTransformer->transformPostDetailPublic($post_detail,$post_photos)

                    ]);
            }
        } else {
            return $this->respondBadRequest('Post not found');
        }
    }

    public function favourites(Request $request)
    {
        $user = $request->user('api');

        $user_likes = User_Post_Like::where('user_id', $user->id)->pluck('post_id');
        if (!$user_likes) {
            return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => null]);
        }

        $favourites = Collect();
        foreach ($user_likes as $like) {
            $post = Post::whereId($like)->first();
            $favourites->push($this->postTransformer->transform($post));
        }

        return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $favourites]);
    }

    private function check_post_id($post_id){
        $post = Post::whereId($post_id)->first();
        return !is_null($post);
    }

    private function check_tags($tags)
    {
        foreach($tags as $tag) {
            $tag = Tag::whereId($tag)->first();
            if (!$tag){
                return false;
            }
        }

        return $tags;
    }

    private function check_photos($photos)
    { 
        foreach($photos as $photo) {
            if (!is_array($photo)) {
                return false;
            }

            $validator = Validator::make($photo, Photo::rules());
            if ($validator->fails()){
                return false;
            }
        }

        return $photos;
    }

    private function post_tags($post_id, $tags)
    {
        foreach($tags as $tag) {
            Post_Tags::create([
                'post_id' => $post_id,
                'tag_id'  => $tag
            ]);
        }
    }

    private function post_photos($post_id, $photos)
    {
        foreach($photos as $photo) {
            Photo::create([
                'post_id'   => $post_id,
                'image'     => $photo['content'],
                'thumbnail' => Photo::createThumbnail($photo['content']),
                'principal' => $photo['principal']
            ]);
        }   
    }
}