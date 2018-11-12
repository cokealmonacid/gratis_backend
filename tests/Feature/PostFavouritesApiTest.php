<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;

class PostFavouritesApiTest extends TestCase
{
	use WithFaker, RefreshDatabase;

	/** @test */
	public function it_get_favourites_user_without_resources()
	{
		$this->user_login();
		$response = $this->getJson("api/v1/favourites");
		$response->assertStatus(200);
	}

    private function user_login()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user, ['api']);
    }
}
