<?php

namespace App\Repositories;

use App\Models\User_Post_Like;
use App\Models\Post;

class UserPostLikeRepository implements RepositoryInterface
{
	protected $user_post_like_model;

	public function __construct(User_Post_Like $user_post_like)
	{
		$this->user_post_like_model = $user_post_like;
	}

	public function all()
	{
		return $this->user_post_like_model->all();
	}

	public function create(array $data)
	{
		return $this->user_post_like_model->create($data);
	}

	public function update(array $data, $id)
	{
		return $this->user_post_like_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->user_post_like_model->destroy($id);
	}

	public function find($id)
	{
		return $this->user_post_like_model->where('user_id', $id)->pluck('post_id');
	}

	public function findPost($user_id, $post_id)
	{
		return $this->user_post_like_model->where(['user_id' => $user_id, 'post_id' => $post_id])->first();
	}

	public function show($user_id, array $data_search){
		$_page          = $data_search["page"];

        $_postsFavoirites = Post::where('state_id','=',1)
		->groupBy('posts.id')
		->join('user_post_likes', 'user_post_likes.post_id', '=','posts.id')
        ->join('photos', 'photos.post_id', '=', 'posts.id')
        ->join('states', 'states.id', '=', 'posts.state_id')
        ->join('provincias','posts.provincia_id','=','provincias.id')
        ->join('regiones','provincias.region_id','=','regiones.id')
        ->leftjoin('post_tags','post_tags.post_id','=','posts.id')
        ->paginate('8',['posts.id as id','posts.publish_date as publishDate','states.id as statesId','states.description as statesDescription','posts.title as title','posts.description as description', 'photos.thumbnail as thumbnail', 'regiones.description as region', 'provincias.description as provincia'],'page',$_page);
		return $_postsFavoirites;
	}

}