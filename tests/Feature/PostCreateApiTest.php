<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Provincia;
use App\Models\Tag;
use App\Models\User;
use App\Models\User_Rol;
use App\Models\Rol;
use Hash;

class PostCreateApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_post_validation_error()
    {
    	$authorization = $this->create_and_login_user();

		$response = $this->postJson('api/v1/posts', [], $authorization);

		$response->assertStatus(422);
    }

    /** @test */
    public function it_throws_tag_validation_error()
    {
    	$authorization = $this->create_and_login_user();

    	$data = [
    		'title'        => $this->faker->words,
    		'description'  => $this->faker->text($maxNbChars = 200),
    		'provincia_id' => Provincia::first()->id,
    		'tags'         => $this->faker->words($nb = 3, $asText = false),
    		'photos'       => $this->faker->words($nb = 3, $asText = false)  
    	];

		$response = $this->postJson('api/v1/posts', $data, $authorization);

		$response->assertStatus(422);
    } 

    /** @test */
    public function it_throws_image_validation_error()
    {
    	$authorization = $this->create_and_login_user();

    	$data = [
    		'title'        => $this->faker->words,
    		'description'  => $this->faker->text($maxNbChars = 200),
    		'provincia_id' => Provincia::first()->id,
    		'tags'         => (array) Tag::first()->id,
    		'photos'       => $this->faker->words($nb = 3, $asText = false)  
    	];

		$response = $this->postJson('api/v1/posts', $data, $authorization);

		$response->assertStatus(422);
    }    

    private function create_and_login_user()
    {
	    /** login */
        $email    = $this->faker->freeEmail();
        $password = $this->faker->word();

        $user = User::create([
            'email'    => $email,
            'password' => Hash::make($password)
        ]);

        $rol = Rol::where('description', 'user')->first();

        User_rol::create([
            'user_id' => $user->id,
            'rol_id'  => $rol->id
        ]);

        $data     = [
            'email'    => $email,
            'password' => $password
        ];

        $response = $this->postJson('api/v1/users/login', $data);
        $_response_content = (object) json_decode($response->content());

        $access_token = $_response_content->client_token->access_token;
        $type_token   = $_response_content->client_token->token_type;

        return [
        	'Content-Type'  => 'application/json',
        	'Authorization' => $type_token . ' ' . $access_token
        ];
    }

}
