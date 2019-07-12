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
        $_image = base64_encode(file_get_contents('https://picsum.photos/600'));
        $_thumbnail = base64_encode(file_get_contents('https://picsum.photos/250'));

        $Posts 		= Post::all();

        foreach ($Posts as $posts) {
            Photo::create([
                'post_id'      => $posts->id,
                'image'     => $_image,
                'thumbnail' => $_thumbnail,
                'extension'        => 'png'
            ]);
        }

    }
}
