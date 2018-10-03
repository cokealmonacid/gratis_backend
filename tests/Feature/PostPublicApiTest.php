<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostPublicApiTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_get_posts_validation_error()
    {
        $_word = $this->faker->word();

        $response = $this->getJson("api/v1/posts/public?page={$_word}");

        $response->assertStatus(422);
    }

    /** @test */
    public function it_get_public_posts() {
        $_page =  rand(1, 15);
        
        $response = $this->getJson("api/v1/posts/public?page={$_page}");

        $response->assertStatus(200);
    }
}
