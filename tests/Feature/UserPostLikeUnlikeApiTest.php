<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Post;
use App\Models\User_Post_Like;
use Laravel\Passport\Passport;

class UserPostLikeUnlikeApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function it_throws_user_post_like_auth_validation_error()
    {

        $post = Post::all()->first();
        $_post_id = $post->id;

        $response = $this->postJson('api/v1/users/post/like', [
            "post_id" => "{$_post_id}"
        ]);

        $response->assertStatus(401);
    }
    /** @test */
    public function it_throws_user_post_like_post_id_validation_error()
    {
        $this->user_login();

        $_post_id = '1234';

        $response = $this->postJson('api/v1/users/post/like', [
            "post_id" => "{$_post_id}"
        ]);

        $response->assertStatus(400);
    }
    /** @test */
    public function it_throws_user_post_like_post_body_validation_error()
    {
        $this->user_login();


        $response = $this->postJson('api/v1/users/post/like', []);

        $response->assertStatus(422);
    }
    /** @test */
    public function it_throws_user_post_like()
    {
        $this->user_login();
        $post = Post::all()->first();
        $_post_id = $post->id;

        $response = $this->postJson('api/v1/users/post/like', [
            "post_id" => "{$_post_id}"
        ]);

        $response->assertStatus(201);
    }




    /** @test */
    public function it_throws_user_post_unlike()
    {
        $user = $this->user_login();

        $post= Post::all()->first();
        User_Post_Like::create(
            [
                'user_id' => $user->id,
                'post_id' => $post->id
            ]
        );
        $response = $this->postJson('api/v1/users/post/like', [
            "post_id" => "{$post->id}"
        ]);

        $response->assertStatus(200);
    }



    private function user_login()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user, ['api']);
        return $user;
    }
}