<?php

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\User;
use App\Models\User_Rol;

class UserRolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Rol::all();
        $users = User::all();

        foreach($users as $user) {

            foreach($roles as $rol) {
                User_Rol::create([
                    'user_id' => $user->id,
                    'rol_id'  => $rol->id
                ]);
            }

        }
    }
}
