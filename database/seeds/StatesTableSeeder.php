<?php

use Illuminate\Database\Seeder;
use App\Models\State;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = ['active', 'inactive', 'finalized'];

        foreach(range(0, count($states) - 1) as $index) {
            State::create([
                'id'          => $index + 1,
                'description' => $states[$index]
            ]);
        }
    }
}
