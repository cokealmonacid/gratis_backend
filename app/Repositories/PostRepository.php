<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Post_Tags;
use App\Models\Photo;
use App\Http\Helper;

class PostRepository implements PostRepositoryInterface
{
	protected $post_model;
	protected $post_tags_model;
	protected $photo_model;

	public function __construct(Post $post, Post_Tags $post_tags_model, Photo $photo_model)
	{
		$this->post_model      = $post;
		$this->post_tags_model = $post_tags_model;
		$this->photo_model     = $photo_model;
	}

	public function all()
	{
		return $this->post_model->all();
    }

	public function create(array $data)
	{
		return $this->post_model->create($data);
	}

	public function createWithPostAndTags($photos, $tags, array $data)
	{
		$post = $this->post_model->create($data);

		$this->post_tags($post['id'], $tags);

		$this->post_photos($post['id'], $photos);

		return $post;
	}

	public function update(array $data, $id)
	{
		return $this->post_model->whereId($id)->update($data);
	}

	public function updateWithPostAndTags($id, $photos, $tags, array $data)
	{
		$post = $this->post_model->whereId($id)->update($data);

		$this->post_tags_model->where('post_id', $id)->delete();

		$this->photo_model->where('post_id', $id)->delete();

		$this->post_tags($id, $tags);

		$this->post_photos($id, $photos, true);

		return $post;
	}

	public function delete($id)
	{
		return $this->post_model->destroy($id);
	}

	public function find($id)
	{
		return $this->post_model->find($id);
	}

	public function show(array $data_filter, array $data_search)
	{
        $_page          = $data_search["page"];
        $_name          = $data_search["name"];
        $_region_id     = $data_search["region_id"];
        $_provincia_id  = $data_search["provincia_id"];
        $_tag_id        = $data_search["tag_id"];

        $_posts = Post::where('state_id','=',1)
            ->where('title', 'like', '%' . $_name . '%')
            ->whereRaw("(provincia_id = '{$_provincia_id}' or '{$_provincia_id}' = '' )")
            ->whereRaw("(regiones.id = '{$_region_id}' or '{$_region_id}' = '' )")
            ->whereRaw("(post_tags.tag_id = '{$_tag_id}' or '{$_tag_id}' = '' )")
            ->groupBy('posts.id')
            ->join('photos', 'photos.post_id', '=', 'posts.id')
            ->join('provincias','posts.provincia_id','=','provincias.id')
            ->join('regiones','provincias.region_id','=','regiones.id')
            ->leftjoin('post_tags','post_tags.post_id','=','posts.id')
            ->paginate('8',['posts.id as id','posts.title as title','posts.description as description', 'photos.url as url', 'regiones.description as region', 'provincias.description as provincia'],'page',$_page)
            ->appends( $data_filter );

        return $_posts;
	}

	function showUserPosts (int $user_id, array $data_search){
		$_page          = $data_search["page"];

        $_postsFavoirites = Post::where('state_id','=',1)
		->groupBy('posts.id')
        ->join('photos', 'photos.post_id', '=', 'posts.id')
        ->join('states', 'states.id', '=', 'posts.state_id')
        ->join('provincias','posts.provincia_id','=','provincias.id')
        ->join('regiones','provincias.region_id','=','regiones.id')
        ->where('posts.user_id', '=' , $user_id )
        ->paginate('8',['posts.id as id','posts.publish_date as publishDate','states.id as statesId','states.description as statesDescription','posts.title as title','posts.description as description', 'photos.url as url', 'regiones.description as region', 'provincias.description as provincia'],'page',$_page);
		return $_postsFavoirites;
	}
    

	public function showDetail($id)
	{
        $post = Post::where('posts.id',$id)
            ->join('users','users.id', '=' ,'posts.user_id')
            ->join('provincias', 'provincias.id', '=', 'posts.provincia_id')
            ->join('regiones', 'regiones.id', '=', 'provincias.region_id')
            ->select(
                'users.id as user_id'
                ,'users.name as user_name'
                ,'users.phone as user_phone'
                ,'users.avatar as user_avatar'
                ,'users.email as user_email'
                ,'posts.id as post_id'
                ,'posts.title as post_title'
                ,'posts.description as post_description'
                ,'provincias.description as provincia_description'
                ,'regiones.description as region_description'
            )->first();


        return $post;
	}

    private function post_tags($post_id, $tags)
    {
        foreach($tags as $tag) {
            $this->post_tags_model->create([
                'post_id' => $post_id,
                'tag_id'  => $tag
            ]);
        }
    }

    private function post_photos($post_id, $photos, $update = false)
    {
        if ($update) {
            $this->removePostPhotos($post_id);
        }

        foreach($photos as $key => $photo) {

            $image = $this->manageImages($photo, $post_id);

            $this->photo_model->create([
                'post_id'   => $post_id,
                'url'       => $image['url'],
                'filename'  => $image['dir'],
                'principal' => $key == 0 ? true : false,
            ]);
        }
    }

    private function manageImages($photo, $post_id)
    {
        $name = $post_id . rand();

        $_image = Helper::resizeImage($photo['content']);

        $image = Helper::uploadImage($post_id, $name, $_image);

        return $image;
    }

    private function removePostPhotos($post_id)
    {
        $photos = $this->post_model->select($post_id);
        foreach($photos as $photo) {
            Helper::deleteImage($photo->filename);
            $this->post_model->delete($photo->id);
        }
    }
}