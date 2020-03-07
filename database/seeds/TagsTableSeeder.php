<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Muebles',
            'Electrodomésticos',
            'Vestuario',
            'Calzado',
        ];

        for ($i = 0; $i < count($categories); $i++) { 
            Tag::create([
                'description' => $categories[$i]
            ]);
        }
    }
}
