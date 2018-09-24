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
        $faker = Faker::create();

        foreach(range(1, 30) as $index) {
            Tag::create([
                'description' => $faker->word
            ]);
        }
    }
}
