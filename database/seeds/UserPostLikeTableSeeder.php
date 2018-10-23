<?php

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\User_Post_Like;


class UserPostLikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Posts 		= Post::all();
        $Users      =User::all()->first();

        foreach ($Posts as $posts) {
            User_Post_Like::create([
                'post_id'      => $posts->id,
                'user_id'     => $Users->id
            ]);
        }

    }
}
