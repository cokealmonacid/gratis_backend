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
    public function it_throws_create_validation_error()
    {
        $this->it_throws_create_email_validation_error();
        $this->it_throws_create_password_validation_error();

    }

    /** @test */
    public function createUser()
    {
        $_email    = $this->faker->freeEmail();
        $_password = "123456789";

        $response = $this->postJson('api/v1/users/',["email" =>"{$_email}", "password" => "{$_password}"]);

        $response->assertStatus(201);
    }

    private  function it_throws_create_email_validation_error()
    {
        $_email    = $this->faker->word();
        $_password = $this->faker->text(8);
        $response = $this->postJson('api/v1/users/',["email" =>"{$_email}", "password" => "{$_password}"]);

        $response->assertStatus(422);

        $_email    = User::all()->first()->email;
        $response = $this->postJson('api/v1/users/',["email" =>"{$_email}", "password" => "{$_password}"]);

        $response->assertStatus(422);

    }
    private  function it_throws_create_password_validation_error()
    {
        $_email    = $this->faker->freeEmail();
        $_password = $this->faker->word();
        $response = $this->postJson('api/v1/users/',["email" =>"{$_email}", "password" => "{$_password}"]);

        $response->assertStatus(422);

    }

}
