<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Post;
use App\Models\Provincia;
use App\Models\User;
use App\Models\State;


class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();

    	for ($i=0; $i < 100; $i++) { 

			$count = rand(3, 5);
    		$user 		= User::inRandomOrder()->first();
    		$state 		= State::inRandomOrder()->first();
    		$provincia 	= Provincia::inRandomOrder()->first();

    		Post::create([
    			'user_id'      => $user->id,
    			'state_id'     => $state->id,
    			'provincia_id' => $provincia->id,
    			'title'        => $faker->sentence($nbWords = $count, $variableNbWords = true),
    			'description'  => $faker->sentence($nbWords = $count*3, $variableNbWords = true),
    			'publish_date' => now()
    		]);
    	}
    }
}
