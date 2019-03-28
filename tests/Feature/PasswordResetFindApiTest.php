<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PasswordReset;

class PasswordResetFindApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_token_validation_error()
    {
    	$token = $this->faker->word();

    	$response = $this->getJson('api/v1/password/find/' . $token);

    	$response->assertStatus(422);
    }

    /** @test */
    public function it_returns_password_reset()
    {
    	$token = str_random(60);

    	$passwordRest = PasswordReset::create([
    		'email' => $this->faker->safeEmail(),
    		'token' => $token
    	]);

    	$response = $this->getJson('api/v1/password/find/' . $token);

    	$response->assertStatus(202);
    }
}
