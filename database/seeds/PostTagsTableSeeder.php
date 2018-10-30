<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Post;
use App\Models\Post_Tags;
use App\Models\Tag;
use App\Models\State;


class PostTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();

    	for ($i=0; $i < 100; $i++) {

    		$tag 		= Tag::inRandomOrder()->first();
    		$post       = Post::inRandomOrder()->first();

    		Post_Tags::create([
    			'post_id'       => $post->id,
    			'tag_id'        => $tag->id
            ]);
    	}
    }
}
