<?php

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::create(['id' => 1,'description' => 'XV Arica y Parinacota' ]);
        Region::create(['id' => 2,'description' => 'I Tarapacá' ]);
        Region::create(['id' => 3,'description' => 'II Antofagasta' ]);
        Region::create(['id' => 4,'description' => 'III Atacama' ]);
        Region::create(['id' => 5,'description' => 'IV Coquimbo' ]);
        Region::create(['id' => 6,'description' => 'V Valparaiso' ]);
        Region::create(['id' => 7,'description' => 'RM Metropolitana de Santiago' ]);
        Region::create(['id' => 8,'description' => 'VI Libertador General Bernardo O\Higgins' ]);
        Region::create(['id' => 9,'description' => 'VII Maule' ]);
        Region::create(['id' => 10,'description' => 'VIII Biobío' ]);
        Region::create(['id' => 11,'description' => 'IX La Araucanía' ]);
        Region::create(['id' => 12,'description' => 'XIV Los Ríos' ]);
        Region::create(['id' => 13,'description' => 'X Los Lagos' ]);
        Region::create(['id' => 14,'description' => 'XI Aisén del General Carlos Ibáñez del Campo' ]);
        Region::create(['id' => 15,'description' => 'XII Magallanes y de la Antártica Chilena' ]);
    }
}
