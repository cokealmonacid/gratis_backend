<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLoginApiTest extends TestCase
{
	/** @test */ 
	public function it_throws_login_validation_error()
	{
		$response = $this->postJson('api/v1/users/login');

		$response->assertStatus(422);
	}
}
