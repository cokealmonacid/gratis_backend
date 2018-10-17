<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Laravel\Passport\Passport;


class UserCreateApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_update_login_validation_error()
    {

        $_email    = $this->faker->freeEmail();
        $_password = $this->faker->text(5);
        $_name     = $this->faker->name();
        $_phone     = $this->faker->phoneNumber();

        $response = $this->putJson('api/v1/users/',[
            "email" =>"{$_email}",
            "password" => "{$_password}",
            "phone" => "{$_phone}",
            "name" => "{$_name}"
        ]);

        $response->assertStatus(401);

    }

    /** @test */
    public function it_throws_update_password_validation_error()
    {
        $this->user_login();

        $_email    = $this->faker->freeEmail();
        $_password = $this->faker->text(5);
        $_name     = $this->faker->name();
        $_phone     = $this->faker->phoneNumber();

        $response = $this->putJson('api/v1/users/',[
            "email" =>"{$_email}",
            "password" => "{$_password}",
            "phone" => "{$_phone}",
            "name" => "{$_name}"
        ]);

        $response->assertStatus(422);

    }

    /** @test */
    public function it_throws_update_unique_email_validation_error()
    {
        $this->user_login();

        $_email    = User::all()->first()->email;
        $_password = $this->faker->text(9);
        $_name     = $this->faker->name();
        $_phone     = $this->faker->phoneNumber();

        $response = $this->putJson('api/v1/users/',[
            "email" =>"{$_email}",
            "password" => "{$_password}",
            "phone" => "{$_phone}",
            "name" => "{$_name}"
        ]);

        $response->assertStatus(422);

    }

    /** @test */
    public function it_throws_create_email_format_validation_error()
    {
        $this->user_login();

        $_email    = $this->faker->word();
        $_password = $this->faker->password();
        $_name     = $this->faker->name();
        $_phone     = $this->faker->phoneNumber();
        $response = $this->putJson('api/v1/users/',[
            "email" =>"{$_email}",
            "password" => "{$_password}",
            "phone" => "{$_phone}",
            "name" => "{$_name}"
        ]);

        $response->assertStatus(422);


    }

    /** @test */
    public function updateUserAllParams()
    {

        $this->user_login();

        $_email     = $this->faker->freeEmail();
        $_password  = $this->faker->password();
        $_name      = $this->faker->name();
        $_phone     = $this->faker->phoneNumber();

        $_data_update =[
            "email"     =>  "{$_email}",
            "password"  =>  "{$_password}",
            "phone"     =>  "{$_phone}",
            "name"      =>  "{$_name}"
        ];

        $response = $this->putJson('api/v1/users/',$_data_update);

        $response->assertStatus(202);
    }

    /** @test */
    public function updateUserPartialsParams()
    {

        $this->user_login();

        $_email     = $this->faker->freeEmail();
        $_password  = $this->faker->password();
        $_name      = $this->faker->name();
        $_phone     = $this->faker->phoneNumber();

        $_data_update =[
            "email" =>"{$_email}",
            "password" => "{$_password}",
            "phone" => "{$_phone}",
            "name" => "{$_name}"
        ];
        if(rand(0,1) < 1) unset($_data_update['email']);
        if(rand(0,1) < 1) unset($_data_update['password']);
        if(rand(0,1) < 1) unset($_data_update['phone']);
        if(rand(0,1) < 1) unset($_data_update['name']);

        $response = $this->putJson('api/v1/users/',$_data_update);


        $response->assertStatus(202);
    }



    private function user_login()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user,['api']);
    }


}
