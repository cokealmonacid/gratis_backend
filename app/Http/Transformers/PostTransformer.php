<?php

namespace App\Http\Transformers;

use App\Http\Transformers\Transformer;

class PostTransformer extends Transformer {

	public function transform($post)
	{
		return [
			'id'          => $post['id'],
			'title'       => $post['title'],
			'description' => $post['description']
		];
	}

    public function transformPostDetailUsers($post_detail,$post_photos){
        return [
            'post' => [
                'id'          => $post_detail['post_id'],
                'title' => $post_detail['post_title'],
                'description' => $post_detail['post_description'],
                'images' => $post_photos
            ],
            'user' => [
                'id'       => $post_detail['user_id'],
                'phone' => $post_detail['user_phone'],
                'email' => $post_detail['user_email'],
                'name' => $post_detail['user_name'],
                'avatar' => $post_detail['user_avatar']
            ]
        ];
    }
    public function transformPostDetailPublic($post_detail,$post_photos){
        return [
            'post' => [
                'id'          => $post_detail['post_id'],
                'title' => $post_detail['post_title'],
                'description' => $post_detail['post_description'],
                'images' => $post_photos
            ],
            'user' => [
                'id'       => $post_detail['user_id'],
                'name' => $post_detail['user_name'],
                'avatar' => $post_detail['user_avatar']
            ]
        ];
    }

}