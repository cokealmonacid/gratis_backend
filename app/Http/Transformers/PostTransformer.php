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
}