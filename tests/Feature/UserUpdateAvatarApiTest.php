<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;

class UserUpdateAvatarApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_user_update_avatar_validation_error()
    {
        $this->user_login();

        $response = $this->putJson('api/v1/users/avatar');

        $response->assertStatus(422);
    }

    /** @test */
    public function it_update_user_avatar()
    {
      	$this->user_login();

    	$data = [
    		'avatar' => $this->create_image()
    	];

    	$response = $this->putJson('api/v1/users/avatar', $data);

        $response->assertStatus(202);
    }

    private function user_login()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user,['api']); 
    }

    private function create_image()
    {
        $path = base_path() . '/tests/data/images.jpeg';
 
        TestCase::assertFileExists($path);

        $image = \Image::make($path)->encode('data-url');

        return $image->encoded;
    }	
}
