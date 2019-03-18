<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\Post;
use App\Models\Provincia;
use App\Models\Tag;
use App\Models\User;

class PostUpdateStateApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_post_validtion_error()
    {
        $this->user_login();

        $post_id = Post::first()->id;

        $response = $this->putJson('api/v1/posts/state/' . $post_id, []);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_throws_post_validtion_state_id_error()
    {
        $this->user_login();

        $post_id = Post::first()->id;

        $data = [
            'state_id'=> $this->faker->numberBetween(10,20),
        ];

        $response = $this->putJson('api/v1/posts/state/' . $post_id, $data);

        $response->assertStatus(422);
    }




    /** @test */
    public function it_throws_user_forbidden_update()
    {
        $user = factory(User::class)->create();

        $this->user_login($user);

        $post = Post::where('user_id', '!=', $user->id)->first();

        $data = [
            'state_id'=> $this->faker->numberBetween(1,3),
        ];

        $response = $this->putJson('api/v1/posts/state/' . $post->id, $data);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_update_post()
    {
      $post = Post::first();

      $user = User::whereId($post->user_id)->first();

      $this->user_login($user);      

    	$data = [
            'state_id'=> $this->faker->numberBetween(1,3),
    	];

      $response = $this->putJson('api/v1/posts/state/' . $post->id, $data);

      $response->assertStatus(200);
    }


    private function user_login($user = null)
    {
      if (!$user) {
        $user = factory(User::class)->create();
      }

      Passport::actingAs($user,['api']); 
    }

}

