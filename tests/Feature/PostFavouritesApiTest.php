<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\Post;
use App\Models\User;
use App\Models\User_Post_Like;

class PostFavouritesApiTest extends TestCase
{
	use WithFaker, RefreshDatabase;

	/** @test */
	public function it_get_favourites_user_without_resources()
	{
		$user = $this->user_login();
		$response = $this->getJson("api/v1/favourites");
		$response->assertStatus(200);
	}

	/** @test */
	public function it_get_favourites_user_with_resources()
	{
		$user = $this->user_login();
        $post= Post::all()->first();

        User_Post_Like::create(
            [
                'user_id' => $user->id,
                'post_id' => $post->id
            ]
        );

        $response = $this->getJson("api/v1/favourites");
        $response_content = json_decode($response->getContent());
        if (is_array($response_content->data) && (!is_null($response_content->data))) {
        	$response->assertStatus(200);
        } else {
        	$response->assertStatus(300);
        }
	}

    private function user_login()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user, ['api']);

        return $user;
    }
}
