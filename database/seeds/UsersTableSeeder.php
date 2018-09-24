<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        User::create([
            'name'      => $faker->firstName,
            'email'     => 'a@gmail.com',
            'password'  => bcrypt('1234'),
            'phone'     => $faker->e164PhoneNumber,
            'api_token' => $faker->uuid
        ]);

        User::create([
            'name'     => $faker->firstName,
            'email'    => 'b@gmail.com',
            'password' => bcrypt('1234'),
            'phone'    => $faker->e164PhoneNumber,
            'api_token' => $faker->uuid
        ]);

        User::create([
            'name'     => $faker->firstName,
            'email'    => 'c@gmail.com',
            'password' => bcrypt('1234'),
            'phone'    => $faker->e164PhoneNumber,
            'api_token' => $faker->uuid
        ]);
    }
}
