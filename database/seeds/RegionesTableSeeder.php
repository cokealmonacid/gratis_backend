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
            'Región de Arica y Parinacota',
            'Región de Tarapacá',
            'Región de Antofagasta',
            'Región de Atacama',
            'Región de Coquimbo',
            'Región de Valparaíso',
            'Región Metropolitana de Santiago',
            'Región del Libertador General Bernardo OHiggins',
            'Región del Maule',
            'Región de Ñuble',
            'Región del Biobío',
            'Región de La Araucanía',
            'Región de Los Ríos',
            'Región de Los Lagos',
            'Región de Aysén del General Carlos Ibáñez del Campo',
            'Región de Magallanes y de la Antártica Chilena'
        ];

        foreach(range(0, count($regions) - 1) as $index) {
            Region::create([
                'id'          => $index + 1,
                'description' => $regions[$index]
            ]);
        }
    }
}
