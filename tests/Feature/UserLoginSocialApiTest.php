<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserLoginSocialApiTest extends TestCase
{
	use WithFaker, RefreshDatabase;

	/** @test */
	public function it_throws_failed_parameters()
	{
		$response = $this->post('api/v1/users/login/facebook');

		$response->assertStatus(422);
	}

	/** @test */
	public function logins_existing_user()
	{
        $email    = $this->faker->freeEmail();
        $password = $this->faker->word();

        $user = User::create(
            [
                'email'     => $email,
                'password'  => bcrypt($password)
            ]
        );

        $data = [
        	'email' => $user->email,
        	'name'  => $this->faker->name,
        	'facebookId' => $this->faker->md5,
        	'facebookToken' => $this->faker->md5,
        	'avatar' => 'https://picsum.photos/600'
        ];

		$response = $this->post('api/v1/users/login/facebook', $data);

		$response->assertStatus(200);
	}

	/** @test */
	public function logins_and_create_user()
	{
        $data = [
        	'email' => $this->faker->freeEmail(),
        	'name'  => $this->faker->name,
        	'facebookId' => $this->faker->md5,
        	'facebookToken' => $this->faker->md5,
        	'avatar' => 'https://picsum.photos/600'
        ];

		$response = $this->post('api/v1/users/login/facebook', $data);

		$response->assertStatus(200);
	}
}
