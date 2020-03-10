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
            'Hogar',
            'Vestuario y calzado',
            'Futura mamá, bebés y niños',
            'Tiempo libre',
            'Computadores y electrónica',
            'Otros productos'
        ];

        for ($i = 0; $i < count($categories); $i++) { 
            Tag::create([
                'description' => $categories[$i]
            ]);
        }
    }
}
