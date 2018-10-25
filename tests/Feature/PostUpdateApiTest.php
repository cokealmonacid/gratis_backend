<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\Post;
use App\Models\Provincia;
use App\Models\Tag;
use App\Models\User;

class PostUpdateApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_post_validtion_error()
    {
      	$this->user_login();

      	$post_id = Post::first()->id;

  		$response = $this->putJson('api/v1/posts/' . $post_id, []);

  		$response->assertStatus(422);
    }

    /** @test */
    public function it_throws_tag_validation_error()
    {
      	$this->user_login(); 

      	$post_id = Post::first()->id;

	    $data = [
	      	'title'        => $this->faker->words,
	      	'description'  => $this->faker->text($maxNbChars = 200),
	      	'provincia_id' => Provincia::first()->id,
	      	'tags'         => $this->faker->words($nb = 3, $asText = false),
	      	'photos'       => $this->faker->words($nb = 3, $asText = false)  
	    ];

     	 $response = $this->putJson('api/v1/posts/' . $post_id, $data);

  		$response->assertStatus(422);
    } 

    /** @test */
    public function it_throws_image_validation_error()
    {
      	$this->user_login(); 

      	$post_id = Post::first()->id;

    	$data = [
    		'title'        => $this->faker->text($maxNbChars = 20),
    		'description'  => $this->faker->text($maxNbChars = 200),
    		'provincia_id' => Provincia::first()->id,
    		'tags'         => (array) Tag::first()->id,
    		'photos'       => $this->faker->words($nb = 3, $asText = false)  
    	];

		$response = $this->putJson('api/v1/posts/' . $post_id, $data);


		$response->assertStatus(422);
    }

    /** @test */
    public function it_throws_provincia_validation_error()
    {
      	$this->user_login();

      	$post_id = Post::first()->id;

      	$data = [
         	'title'        => $this->faker->text($maxNbChars = 20),
         	'description'  => $this->faker->text($maxNbChars = 200),
         	'provincia_id' => 0,
         	'tags'         => [ Tag::first()->id ],
         	'photos'       => [ $this->create_image() ]
      	];

		$response = $this->putJson('api/v1/posts/' . $post_id, $data);

      	$response->assertStatus(422);
    }

    /** @test */
    public function it_throws_post_validation_error()
    {
     	$this->user_login();

      	$post_id = $this->faker->word;

      	$data = [
          	'title'        => $this->faker->text($maxNbChars = 20),
          	'description'  => $this->faker->text($maxNbChars = 200),
          	'provincia_id' => Provincia::first()->id,
          	'tags'         => [ Tag::first()->id ],
          	'photos'       => [ $this->create_image() ]
      	];

      	$response = $this->putJson('api/v1/posts/' . $post_id, $data);

      	$response->assertStatus(400);
    }

    /** @test */
    public function it_update_post()
    {
      	$this->user_login();

      	$post_id = Post::first()->id;

      	$data = [
        	'title'        => $this->faker->text($maxNbChars = 20),
        	'description'  => $this->faker->text($maxNbChars = 200),
        	'provincia_id' => Provincia::first()->id,
        	'tags'         => [ Tag::first()->id ],
        	'photos'       => [ $this->create_image() ]
      	];

       $response = $this->putJson('api/v1/posts/' . $post_id, $data);

       $response->assertStatus(200);
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

        return [
            'content'   => explode(",", (string) $image)[1],
            'extension' => $image->mime,
            'principal' => false
        ];
    }
}

