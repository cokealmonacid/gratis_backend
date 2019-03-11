<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\Report_Reason;
use App\Models\Post;
use App\Models\User;

class ReportPostApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_validation_error()
    {
    	$this->user_login();

    	$response = $this->postJson('api/v1/reasons', []);

    	$response->assertStatus(422);
    }    

    /** @test */
    public function it_throws_post_doesnt_exists()
    {
    	$this->user_login();

    	$report_id = Report_Reason::first()->id;

    	$data = [
    		'report_id' => $report_id,
    		'post_id'   => $this->faker->words
    	];

    	$response = $this->postJson('api/v1/reasons', $data);

    	$response->assertStatus(500);
    }

    /** @test */
    public function it_throws_report_doesnt_exists()
    {
    	$this->user_login();

    	$post_id = Post::first()->id;

    	$data = [
    		'report_id' => $this->faker->words,
    		'post_id'   => $post_id
    	];

    	$response = $this->postJson('api/v1/reasons', $data);

    	$response->assertStatus(500);
    }

    /** @test */
    public function it_create_a_report()
    {
    	$this->user_login();

    	$post_id = Post::first()->id;

    	$report_id = Report_Reason::first()->id;

    	$data = [
    		'report_id' => $report_id,
    		'post_id'   => $post_id
    	];

    	$response = $this->postJson('api/v1/reasons', $data);

    	$response->assertStatus(201);
    }

    private function user_login($user = null)
    {
      if (!$user) {
        $user = factory(User::class)->create();
      }

      Passport::actingAs($user,['api']); 
    }
}
