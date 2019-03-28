<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\User_Rol;
use App\Models\Rol;

class PasswordResetCreateApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_post_validation_error()
    {
    	$response = $this->postJson('api/v1/password/create', []);

    	$response->assertStatus(422);
    }

    /** @test */
    public function it_throws_email_validation_error()
    {
        $data = ['email' => $this->faker->freeEmail()];

        $response = $this->postJson('api/v1/password/create', $data);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_create_password_reset()
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
        ];

        $response = $this->postJson('api/v1/password/create', $data);

        $response->assertStatus(200);
    }
}
