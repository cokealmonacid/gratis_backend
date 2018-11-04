<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Post_Tags;
use App\Models\Region;
use App\Models\Provincia;

class PostPublicFilterApiTestApiTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_throws_get_posts_page_validation_error()
    {
        $_page = $this->faker->word();

        $_params = [
          "title"           => "",
          "provincia_id"    => "",
          "region_id"       => "",
          "tag_id"          => "",
          "page"            => $_page
        ];
        $_http_params = http_build_query($_params);
        $response = $this->getJson("api/v1/posts/public?{$_http_params}");
        $response->assertStatus(422);
    }

    /** @test */
    public function it_throws_get_posts_provincia_id_validation_error()
    {
        $_page = 1;

        $_params = [
            "title"         => "",
            "provincia_id"  => $this->faker->word(),
            "region_id"     => "",
            "tag_id"        => "",
            "page"            => $_page
        ];

        $_http_params = http_build_query($_params);
        $response = $this->getJson("api/v1/posts/public?{$_http_params}");
        $response->assertStatus(422);
    }

    /** @test */
    public function it_throws_get_posts_region_id_validation_error()
    {
        $_page = 1;

        $_params = [
            "title"         => "",
            "provincia_id"  => "",
            "region_id"     => $this->faker->word(),
            "tag_id"        => "",
            "page"          => $_page
        ];

        $_http_params = http_build_query($_params);
        $response = $this->getJson("api/v1/posts/public?{$_http_params}");
        $response->assertStatus(422);
    }

    /** @test */
    public function it_throws_get_posts_tag_id_validation_error()
    {
        $_page = 1;

        $_params = [
            "title"         => "",
            "provincia_id"  => "",
            "region_id"     => "",
            "tag_id"        => $this->faker->word(),
            "page"          => $_page
        ];

        $_http_params = http_build_query($_params);
        $response = $this->getJson("api/v1/posts/public?{$_http_params}");
        $response->assertStatus(422);
    }

    /** @test */
    public function it_get_posts_whit_empy_params()
    {
        $_page = 1;

        $_params = [
            "title"         => "",
            "provincia_id"  => "",
            "region_id"     => "",
            "tag_id"        => "",
            "page"          => $_page
        ];

        $_http_params = http_build_query($_params);
        $response = $this->getJson("api/v1/posts/public?{$_http_params}");
        $response->assertStatus(200);
    }

    /** @test */
    public function it_get_posts_whit_void_params()
    {


        $response = $this->getJson("api/v1/posts/public",[]);
        $response->assertStatus(200);
    }


    /** @test */
    public function it_get_public_posts_all_params() {
        $_page = 1;

        $post_tag = Post_Tags::inRandomOrder()->first();
        $region= Region::inRandomOrder()->first();
        $provincia = Provincia::inRandomOrder()->first();

        $_params = [
            "title"         => $this->faker->word(),
            "provincia_id"  => $provincia->id,
            "region_id"     => $region->id,
            "tag_id"        => $post_tag->tag_id,
            "page"            => $_page
        ];

        $_http_params = http_build_query($_params);
        $response = $this->getJson("api/v1/posts/public?{$_http_params}");
        $response->assertStatus(200);
    }
}
