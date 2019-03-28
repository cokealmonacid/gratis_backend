<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PasswordReset;
use App\Models\User;

class PasswordResetApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_validates_reset_password()
    {
    	$response = $this->postJson('api/v1/password/reset', []);

    	$response->assertStatus(422);
    }

    /** @test */
    public function it_throws_email_doesnt_exists()
    {
        $data = [
        	'email' => $this->faker->freeEmail(),
        	'password' => 12345678,
        	'token' => $this->faker->word()
        ];


    	$response = $this->postJson('api/v1/password/reset', $data);

    	$response->assertStatus(422);
    }


    /** @test */
    public function it_throws_token_doesnt_exists()
    {
        $data = [
        	'email' => $this->create_user(),
        	'password' => 12345678,
        	'token' => $this->faker->word()
        ];

    	$response = $this->postJson('api/v1/password/reset', $data);

    	$response->assertStatus(422);
    }

    /** @test */
    public function it_reset_password()
    {
    	$token = str_random(60);

    	$email = $this->create_user();

    	$passwordRest = PasswordReset::create([
    		'email' => $email,
    		'token' => $token
    	]);

    	$data = [
    		'email'    => $email,
    		'password' => 12345678,
    		'token'    => $token
    	];

    	$response = $this->postJson('api/v1/password/reset', $data);

    	$response->assertStatus(400);
    }

    private function create_user()
    {
        $email    = $this->faker->freeEmail();
        $password = $this->faker->word();

        $user = User::create([
            'email'    => $email,
            'password' => $password,
        ]);

        return $email;
    }
}
