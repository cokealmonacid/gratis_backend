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
        $_image = base64_encode(file_get_contents('https://dummyimage.com/650x400/999/FFF'));
        $_thumbnail = base64_encode(file_get_contents('https://dummyimage.com/250x250/999/FFF'));

        $Posts 		= Post::all();

        foreach ($Posts as $posts) {
            Photo::create([
                'post_id'      => $posts->id,
                'image'     => $_image,
                'thumbnail' => $_thumbnail,
                'extension'        => 'png'
            ]);        }

    }
}
