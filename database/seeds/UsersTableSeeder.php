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
            'email'     => 'a@test.com',
            'password'  => bcrypt('12345678'),
            'phone'     => $faker->e164PhoneNumber,
        ]);

        User::create([
            'name'     => $faker->firstName,
            'email'    => 'b@test.com',
            'password' => bcrypt('12345678'),
            'phone'    => $faker->e164PhoneNumber,
        ]);

        User::create([
            'name'     => $faker->firstName,
            'email'    => 'c@test.com',
            'password' => bcrypt('12345678'),
            'phone'    => $faker->e164PhoneNumber,
        ]);
    }
}
