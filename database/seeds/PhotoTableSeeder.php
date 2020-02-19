<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Post;
use App\Models\Photo;


class PhotoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Posts 		= Post::all();

        foreach ($Posts as $posts) {
            Photo::create([
                'post_id'      => $posts->id,
                'url'          => 'https://picsum.photos/600',
                'principal'    => true
            ]);
        }

    }
}
