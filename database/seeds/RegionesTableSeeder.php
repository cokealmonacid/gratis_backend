<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RegionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('regiones')->insert([
            'description' => str_random(10)
        ]);
    }
}
