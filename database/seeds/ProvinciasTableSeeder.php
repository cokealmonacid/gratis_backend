<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Provincia;
use App\Models\Region;

class ProvinciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provincia::create(['id' => 1,'description' => 'Arica', 'region_id' => 1  ]);
        Provincia::create(['id' => 2,'description' => 'Parinacota', 'region_id' => 1  ]);
        Provincia::create(['id' => 3,'description' => 'Iquique', 'region_id' => 2  ]);
        Provincia::create(['id' => 4,'description' => 'El Tamarugal', 'region_id' => 2  ]);
        Provincia::create(['id' => 5,'description' => 'Antofagasta', 'region_id' => 3  ]);
        Provincia::create(['id' => 6,'description' => 'El Loa', 'region_id' => 3  ]);
        Provincia::create(['id' => 7,'description' => 'Tocopilla', 'region_id' => 3  ]);
        Provincia::create(['id' => 8,'description' => 'Chañaral', 'region_id' => 4  ]);
        Provincia::create(['id' => 9,'description' => 'Copiapó', 'region_id' => 4  ]);
        Provincia::create(['id' => 10,'description' => 'Huasco', 'region_id' => 4  ]);
        Provincia::create(['id' => 11,'description' => 'Choapa', 'region_id' => 5  ]);
        Provincia::create(['id' => 12,'description' => 'Elqui', 'region_id' => 5  ]);
        Provincia::create(['id' => 13,'description' => 'Limarí', 'region_id' => 5  ]);
        Provincia::create(['id' => 14,'description' => 'Isla de Pascua', 'region_id' => 6  ]);
        Provincia::create(['id' => 15,'description' => 'Los Andes', 'region_id' => 6  ]);
        Provincia::create(['id' => 16,'description' => 'Petorca', 'region_id' => 6  ]);
        Provincia::create(['id' => 17,'description' => 'Quillota', 'region_id' => 6  ]);
        Provincia::create(['id' => 18,'description' => 'San Antonio', 'region_id' => 6  ]);
        Provincia::create(['id' => 19,'description' => 'San Felipe de Aconcagua', 'region_id' => 6  ]);
        Provincia::create(['id' => 20,'description' => 'Valparaiso', 'region_id' => 6  ]);
        Provincia::create(['id' => 21,'description' => 'Chacabuco', 'region_id' => 7  ]);
        Provincia::create(['id' => 22,'description' => 'Cordillera', 'region_id' => 7  ]);
        Provincia::create(['id' => 23,'description' => 'Maipo', 'region_id' => 7  ]);
        Provincia::create(['id' => 24,'description' => 'Melipilla', 'region_id' => 7  ]);
        Provincia::create(['id' => 25,'description' => 'Santiago', 'region_id' => 7  ]);
        Provincia::create(['id' => 26,'description' => 'Talagante', 'region_id' => 7  ]);
        Provincia::create(['id' => 27,'description' => 'Cachapoal', 'region_id' => 8  ]);
        Provincia::create(['id' => 28,'description' => 'Cardenal Caro', 'region_id' => 8  ]);
        Provincia::create(['id' => 29,'description' => 'Colchagua', 'region_id' => 8  ]);
        Provincia::create(['id' => 30,'description' => 'Cauquenes', 'region_id' => 9  ]);
        Provincia::create(['id' => 31,'description' => 'Curicó', 'region_id' => 9  ]);
        Provincia::create(['id' => 32,'description' => 'Linares', 'region_id' => 9  ]);
        Provincia::create(['id' => 33,'description' => 'Talca', 'region_id' => 9  ]);
        Provincia::create(['id' => 34,'description' => 'Arauco', 'region_id' => 10  ]);
        Provincia::create(['id' => 35,'description' => 'Bio Bío', 'region_id' => 10  ]);
        Provincia::create(['id' => 36,'description' => 'Concepción', 'region_id' => 10  ]);
        Provincia::create(['id' => 37,'description' => 'Ñuble', 'region_id' => 10  ]);
        Provincia::create(['id' => 38,'description' => 'Cautín', 'region_id' => 11  ]);
        Provincia::create(['id' => 39,'description' => 'Malleco', 'region_id' => 11  ]);
        Provincia::create(['id' => 40,'description' => 'Valdivia', 'region_id' => 12  ]);
        Provincia::create(['id' => 41,'description' => 'Ranco', 'region_id' => 12  ]);
        Provincia::create(['id' => 42,'description' => 'Chiloé', 'region_id' => 13  ]);
        Provincia::create(['id' => 43,'description' => 'Llanquihue', 'region_id' => 13  ]);
        Provincia::create(['id' => 44,'description' => 'Osorno', 'region_id' => 13  ]);
        Provincia::create(['id' => 45,'description' => 'Palena', 'region_id' => 13  ]);
        Provincia::create(['id' => 46,'description' => 'Aisén', 'region_id' => 14  ]);
        Provincia::create(['id' => 47,'description' => 'Capitán Prat', 'region_id' => 14  ]);
        Provincia::create(['id' => 48,'description' => 'Coihaique', 'region_id' => 14  ]);
        Provincia::create(['id' => 49,'description' => 'General Carrera', 'region_id' => 14  ]);
        Provincia::create(['id' => 50,'description' => 'Antártica Chilena', 'region_id' => 15  ]);
        Provincia::create(['id' => 51,'description' => 'Magallanes', 'region_id' => 15  ]);
        Provincia::create(['id' => 52,'description' => 'Tierra del Fuego', 'region_id' => 15  ]);
        Provincia::create(['id' => 53,'description' => 'Última Esperanza', 'region_id' => 15  ]);
    }
}
