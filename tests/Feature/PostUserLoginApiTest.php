<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;

class PostUserLoginApiTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_get_posts_validation_error()
    {
        $_word = $this->faker->word();
		$this->user_login();

        $response = $this->getJson("api/v1/posts?page={$_word}");

        $response->assertStatus(422);
    }


    /** @test */
    public function it_get_public_posts() {
        $this->user_login();

        $_page =  rand(1, 8);
        
        $response = $this->getJson("api/v1/posts?page={$_page}");

        $response->assertStatus(200);
    }


    private function user_login()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user, ['api']);
    }
}
