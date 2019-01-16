<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Report_Reason;

class ReportReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();

    	for ($i=0; $i < 10; $i++) { 

    		Report_Reason::create([
    			'description'  => $faker->text($maxNbChars = 20)   
    		]);
    	}
    }
}
