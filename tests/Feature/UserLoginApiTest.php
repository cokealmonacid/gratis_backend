<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Models\User_Rol;
use App\Models\Rol;
use Hash;

class UserLoginApiTest extends TestCase
{
	use WithFaker, RefreshDatabase;
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

		$user = User::create([
			'email'    => $email,
			'password' => $password,
		]);

		$rol = Rol::where('description', 'admin')->first();

		User_rol::create([
			'user_id' => $user->id,
			'rol_id'  => $rol->id
		]);

		$data     = [
			'email'    => $email,
			'password' => $password 
		];

		$response = $this->postJson('api/v1/users/login', $data);

		$response->assertStatus(400);
	}

	/** @test */
	public function it_throws_email_and_password_dont_match()
	{
		$user = User::first();

		$data = [
			'email'    => $user->email,
			'password' => $user->email
		];

		$response = $this->postJson('api/v1/users/login', $data);

		$response->assertStatus(400);
	}

	/** @test */
	public function it_login_a_user()
	{
		$email    = $this->faker->freeEmail();
		$password = $this->faker->word();

        $user = User::create(
            [
                'email'     => $email,
                'password'  => bcrypt($password)
            ]
        );

		$rol = Rol::where('description', 'user')->first();

		User_rol::create([
			'user_id' => $user->id,
			'rol_id'  => $rol->id
		]);

		$data     = [
			'email'    => $email,
			'password' => $password
		];
		$response = $this->postJson('api/v1/users/login',["email" =>"{$email}", "password" => "{$password}"]);

		$response->assertStatus(200);
	}

    /** @test */
	public function it_logout_a_user(){
	    /** login */
		$user = factory(User::class)->create();

		Passport::actingAs($user,['api']); 

        $response = $this->postJson('api/v1/users/logout',[]);

        $response->assertStatus(200);

    }
}
