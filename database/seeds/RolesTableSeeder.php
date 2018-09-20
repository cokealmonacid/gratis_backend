<?php

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['user', 'admin'];

        foreach(range(0, count($roles) - 1) as $index) {
            Rol::create([
                'id'          => $index + 1,
                'description' => $roles[$index]
            ]);
        }
    }
}
