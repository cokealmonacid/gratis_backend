<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserCreateApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function createUser()
    {
        $_name     = $this->faker->text($minNbChars = 8);
        $_email    = $this->faker->freeEmail();
        $_password = "123456789";

        $response = $this->postJson('api/v1/users/',["name" =>"{$_name}", "email" =>"{$_email}", "password" => "{$_password}"]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_throws_create_email_format_validation_error()
    {
        $_name     = $this->faker->text($minNbChars = 8);
        $_email    = $this->faker->word();
        $_password = $this->faker->text(8);
        $response = $this->postJson('api/v1/users/',["name" =>"{$_name}", "email" =>"{$_email}", "password" => "{$_password}"]);

        $response->assertStatus(422);


    }
    /** @test */
    public function it_throws_create_unique_email_validation_error()
    {
        $_name     = $this->faker->text($minNbChars = 8);
        $_password = $this->faker->text(8);
        $_email    = User::all()->first()->email;
        $response = $this->postJson('api/v1/users/',["name" =>"{$_name}", "email" =>"{$_email}", "password" => "{$_password}"]);

        $response->assertStatus(422);

    }

    /** @test */
    public function it_throws_create_password_validation_error()
    {
        $_name     = $this->faker->text($minNbChars = 8);
        $_email    = $this->faker->freeEmail();
        $_password = $this->faker->text(5);
        $response = $this->postJson('api/v1/users/',["name" =>"{$_name}", "email" =>"{$_email}", "password" => "{$_password}"]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_throws_create_name_validation_error()
    {
        $_name     = $this->faker->text($maxNbChars = 7);
        $_email    = $this->faker->freeEmail();
        $_password = $this->faker->text(5);
        $response = $this->postJson('api/v1/users/',["name" =>"{$_name}", "email" =>"{$_email}", "password" => "{$_password}"]);

        $response->assertStatus(422);
    }

}
