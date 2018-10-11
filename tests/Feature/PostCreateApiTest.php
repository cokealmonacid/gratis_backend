<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
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
        $this->user_login();   

		$response = $this->postJson('api/v1/posts', []);

		$response->assertStatus(422);
    }

    /** @test */
    public function it_throws_tag_validation_error()
    {
        $this->user_login(); 

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
        $this->user_login(); 

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

    private function user_login()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user,['api']); 
    }

}
