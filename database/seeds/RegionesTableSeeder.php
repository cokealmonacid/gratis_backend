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
        $regions = [
            'XV Arica y Parinacota',
            'I Tarapacá',
            'II Antofagasta',
            'III Atacama',
            'IV Coquimbo',
            'V Valparaíso',
            'Región Metropolitana',
            'VI O´Higgins',
            'VII Maule',
            'XVI Ñuble',
            'VIII Biobío',
            'XI Araucanía',
            'XIV Los Ríos',
            'X Los Lagos',
            'XI Aysén',
            'XII Magallanes y Antártica'
        ];

        foreach(range(0, count($regions) - 1) as $index) {
            Region::create([
                'id'          => $index + 1,
                'description' => $regions[$index]
            ]);
        }
    }
}
