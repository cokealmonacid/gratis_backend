<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Provincia;
use App\Models\Region;

class ProvinciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();

        $regiones = Region::all();

        foreach($regiones as $region){

        	$count = rand(1, 5);
        	for ($i=0; $i < $count; $i++) { 
        		Provincia::create([
        			'description' => $faker->city,
        			'region_id'   => $region->id
        		]);
        	}

        }
    }
}
