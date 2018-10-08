<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\User_Rol;
use App\Models\Rol;

class UserLoginApiTest extends TestCase
{
	use WithFaker;

	/** @test */ 
	public function it_throws_login_validation_error()
	{
		$response = $this->postJson('api/v1/users/login');

		$response->assertStatus(422);
	}

	/** @test */
	public function it_throws_email_account_does_not_exist()
	{
		$email    = $this->faker->freeEmail();
		$password = $this->faker->word();

		$data     = [
			'email'    => $email,
			'password' => $password 
		];

		$response = $this->postJson('api/v1/users/login', $data);

		$response->assertStatus(400);
	}

	/** @test */
	public function it_throws_email_has_no_user_role()
	{
		$email    = $this->faker->freeEmail();
		$password = $this->faker->word();
		$name     = $this->faker->firstNameMale();

		$user = User::create([
			'email'    => $email,
			'password' => $password,
			'name'     => $name
		]);

		$rol = Rol::where('description', 'admin')->first();

		User_rol::create([
			'user_id' => $user->id->toString(),
			'rol_id'  => $rol->id
		]);

		$data     = [
			'email'    => $email,
			'password' => $password 
		];

		$response = $this->postJson('api/v1/users/login', $data);
		
		$response->assertStatus(400);
	}
}
