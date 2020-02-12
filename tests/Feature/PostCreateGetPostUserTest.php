<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;

class PostCreateGetPostUserTest extends TestCase
{


    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_get_posts_validation_error()
    {
        $_word = $this->faker->word();
        $user_id= User::all()->shuffle()->first()->id;

        $response = $this->getJson("api/v1/posts/user/{$user_id}?page={$_word}");

        $response->assertStatus(422);
    }

    /** @test */
    public function it_get_user_posts() {
        $user_id= User::all()->shuffle()->first()->id;
        $response = $this->getJson("api/v1/posts/user/{$user_id}");
        $response->assertStatus(200);
    }
    
}
