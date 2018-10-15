<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

use App\Models\User;

use App\Models\Post;
use Hash;

class PostGetDetailsApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_post_validation_error()
    {
        // $this->user_login();
        $_fake_id = $this->faker->text($maxNbChars = 20);
        $response = $this->getJson("api/v1/posts/{$_fake_id}");
        $response->assertStatus(400);
    }
    /** @test */
    public function it_get_post_detail_public()
    {
        // $this->user_login();
        $_id_post = $this->getIdPostRandom ();
        $response = $this->getJson("api/v1/posts/{$_id_post}");
        $response_user_content = json_decode($response->getContent())->data->user;
        if(!$this->is_private_params_user($response_user_content)) {
            $response->assertStatus(200);
        }
        else{
            $this->fail('The user private information is found in the response');

        }
    }
    /** @test */
    public function it_get_post_detail_users()
    {
        $this->user_login();
        $_id_post = $this->getIdPostRandom ();
        $response = $this->getJson("api/v1/posts/{$_id_post}");
        $response_user_content = json_decode($response->getContent())->data->user;
        if($this->is_private_params_user($response_user_content)){
            $response->assertStatus(200);

        }
        else{
            $this->fail('The user private information is not found in the response');
        }
    }

    private function is_private_params_user($user_post_details){

        return isset($user_post_details->phone) && isset($user_post_details->email);
    }

    private function getIdPostRandom () {
        $post = Post::all()->random(1)->first();
        return $post['id'];
    }

    private function user_login()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user,['api']);
    }
}
