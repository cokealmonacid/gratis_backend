<?php

namespace App\Http\Controllers\API;

use App\Repositories\PostRepository as PostRepository;
use App\Repositories\PostTagsRepository as PostTagsRepository;
use App\Repositories\PhotoRepository as PhotoRepository;
use App\Repositories\ProvinciaRepository as ProvinciaRepository;
use App\Repositories\TagRepository as TagRepository;
use App\Repositories\UserPostLikeRepository as UserPostLikeRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Transformers\PostTransformer;
use App\Models\Post;
use App\Models\Photo;
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
    function __construct(PostTransformer $postTransformer, PostRepository $postRepository, 
        ProvinciaRepository $provinciaRepository, PostTagsRepository $postTagsRepository,
        PhotoRepository $photoRepository, UserPostLikeRepository $userPostLikeRepository,
        TagRepository $tagRepository)
    {
        $this->postRepository         = $postRepository;
        $this->postTagsRepository     = $postTagsRepository;
        $this->postTransformer        = $postTransformer;
        $this->provinciaRepository    = $provinciaRepository;
        $this->photoRepository        = $photoRepository;
        $this->userPostLikeRepository = $userPostLikeRepository;
        $this->tagRepository          = $tagRepository;
    }

    public function show(Request $request) 
    {
        $validator = Validator::make($request->all(), Post::rulesFilter());

        if ($validator->fails()) {
            return $this->respondFailedParametersValidation($validator->errors()->first());
        }

        $_posts = $this->postRepository->show($request);

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

        $provincia = $this->provinciaRepository->find($request->input('provincia_id'));
        if (!$provincia) {
            return $this->respondFailedParametersValidation('Paramaters failed validation for a post');
        }

        $user = Auth::guard('api')->user();
        $post = $this->postRepository->create([
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

        $provincia = $this->provinciaRepository->find($request->input('provincia_id'));
        if (!$provincia) {
            return $this->respondFailedParametersValidation('Paramaters failed validation for a post');
        }

        $post = $this->postRepository->find($id);
        if (!$post) {
            return $this->respondBadRequest('This post does not exist');
        }

        $user = Auth::guard('api')->user();
        if ($post->user_id != $user->id) {
            return $this->respondForbidden();
        }

        $this->postRepository->update($post->id, [
            'title'        => $request->input('title'),
            'description'  => $request->input('description'),
            'provincia_id' => $provincia->id
        ]);

        $tags_delete = $this->postTagsRepository->delete($post->id);
        if ($tags_delete) {
            $this->post_tags($post->id, $tags);
        }

        $photos_delete = $this->photoRepository->delete($post->id);
        if ($photos_delete) {
            $this->post_photos($post->id, $photos);
        }

        return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $this->postTransformer->transform($post)]);
    }

    public function showDetail($id, Request $request) 
    {
        $post = $this->postRepository->find($id);
        if ($post) {

            $post_detail = $this->postRepository->showDetail($post->id);

            $post_photos = $this->photoRepository->select($post->id);
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

        $user_likes = $this->userPostLikeRepository->find($user->id);
        if (!$user_likes) {
            return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => null]);
        }

        $favourites = Collect();
        foreach ($user_likes as $like) {
            $post = $this->postRepository->find($like);
            $favourites->push($this->postTransformer->transform($post));
        }

        return $this->setStatusCode(Response::HTTP_OK)->respond(['data' => $favourites]);
    }

    private function check_tags($tags)
    {
        foreach($tags as $tag_id) {
            $tag = $this->tagRepository->find($tag_id);
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
            $this->postTagsRepository->create([
                'post_id' => $post_id,
                'tag_id'  => $tag
            ]);
        }
    }

    private function post_photos($post_id, $photos)
    {
        foreach($photos as $photo) {
            $this->photoRepository->create([
                'post_id'   => $post_id,
                'image'     => $photo['content'],
                'thumbnail' => Photo::createThumbnail($photo['content']),
                'principal' => $photo['principal']
            ]);
        }   
    }
}