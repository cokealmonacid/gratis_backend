<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Mockery;
use Socialite;

class UserLoginSocialApiTest extends TestCase
{
	use WithFaker, RefreshDatabase;

	/** @test */
	public function it_redirects_to_facebook()
	{
		$response = $this->get('api/v1/users/login/redirect/facebook');

		$this->assertContains('facebook.com/v3.0/dialog/oauth', $response->getTargetUrl());
	}

	/** @test */
	public function it_retrieves_facebook_request_and_login_user_with_email()
	{
		$user = factory(User::class)->create();

		$this->mockSocialite($user->email);

		$response = $this->get('api/v1/users/login/facebook');

		$response->assertStatus(200);
	}

	/** @test */
	public function it_retrieves_facebook_request_and_login_user_with_id()
	{
		$user = factory(User::class)->create([
			'provider_id' => rand()
		]);

		$this->mockSocialite(null, null, $user->provider_id);

		$response = $this->get('api/v1/users/login/facebook');

		$response->assertStatus(200);
	}

	/** @test */
	public function it_retrieves_facebook_request_and_create_a_user()
	{
		$this->mockSocialite();

		$response = $this->get('api/v1/users/login/facebook');

		$response->assertStatus(200);
	}

	private function mockSocialite($email = 'foo@bar.com', $name = 'foo', $id = 1)
	{
		$socialiteUser = Mockery::mock('Laravel\Socialite\Two\FacebookProvider');
	    $socialiteUser->shouldReceive('getEmail')
	    	->andReturn($email);

	    $socialiteUser->shouldReceive('getId')
	    	->andReturn($id);

	    $socialiteUser->shouldReceive('getName')
	    	->andReturn('Jorge');

        $mock = Socialite::shouldReceive('stateless')
            ->andReturn(Mockery::self())
            ->shouldReceive('driver')
            ->with('facebook')
            ->andReturn(Mockery::self());

        $mock->shouldReceive('user')->andReturn($socialiteUser);
	}
}
