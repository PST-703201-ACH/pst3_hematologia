<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Parroquia;

class GeografiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Instanciamos el objeto Estado para Distrito Capital
        $distritoCapital = Estado::create(['nombre' => 'Distrito Capital']);

        // 2. Creamos el Municipio Libertador asociado a ese Estado
        $municipioLibertador = Municipio::create([
            'estado_id' => $distritoCapital->id,
            'nombre' => 'Libertador'
        ]);

        // 3. Creamos las Parroquias clave asociadas al municipio
        Parroquia::create(['municipio_id' => $municipioLibertador->id, 'nombre' => 'Antímano']);
        Parroquia::create(['municipio_id' => $municipioLibertador->id, 'nombre' => 'Sucre (Catia)']);
        Parroquia::create(['municipio_id' => $municipioLibertador->id, 'nombre' => 'El Recreo']);
        Parroquia::create(['municipio_id' => $municipioLibertador->id, 'nombre' => 'San Juan']);
        Parroquia::create(['municipio_id' => $municipioLibertador->id, 'nombre' => 'Catedral']);
    }
}