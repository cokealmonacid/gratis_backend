<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
	protected $post_model;

	public function __construct(Post $post)
	{
		$this->post_model = $post;
	}

	public function all()
	{
		return $this->post_model->all();
	}	

	public function create(array $data)
	{
		return $this->post_model->create($data);
	}

	public function update($id, array $data)
	{
		return $this->post_model->whereId($id)->update($data);
	}

	public function delete($id)
	{
		return $this->post_model->destroy($id);
	}

	public function find($id)
	{
		return $this->post_model->find($id);
	}

	public function show(object $data)
	{
        $data_filter    = $data->only('title', 'region_id', 'provincia_id','tag_id');
        $_page          = $data->input('page');
        $_name          = $data->input('title');
        $_region_id     = $data->input('region_id');
        $_provincia_id  = $data->input('provincia_id');
        $_tag_id        = $data->input('tag_id');

        $_posts = Post::where('state_id','=',1)
            ->where('title', 'like', '%' . $_name . '%')
            ->whereRaw("(provincia_id = '{$_region_id}' or '{$_region_id}' = '' )")
            ->whereRaw("(regiones.id = '{$_provincia_id}' or '{$_provincia_id}' = '' )")
            ->whereRaw("(post_tags.tag_id = '{$_tag_id}' or '{$_tag_id}' = '' )")
            ->groupBy('posts.id')
            ->join('photos', 'photos.post_id', '=', 'posts.id')
            ->join('provincias','posts.provincia_id','=','provincias.id')
            ->join('regiones','provincias.region_id','=','regiones.id')
            ->leftjoin('post_tags','post_tags.post_id','=','posts.id')
            ->paginate('5',['posts.id as id','posts.title as title','posts.description as description', 'photos.thumbnail as thumbnail'],'page',$_page)
            ->appends( $data_filter );

        return $_posts;
	}

	public function showDetail($id)
	{
		$post = Post::whereId($id)
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
            )->first();

        return $post;
	}
}