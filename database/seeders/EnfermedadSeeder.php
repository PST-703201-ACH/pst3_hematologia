<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enfermedad;

class EnfermedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sembramos códigos reales de la clasificación internacional CIE-10
        Enfermedad::create([
            'codigo_cie10' => 'D50',
            'nombre'       => 'Anemia por deficiencia de hierro',
            'descripcion'  => 'Disminución de los glóbulos rojos debido a la falta de hierro en el organismo.'
        ]);

        Enfermedad::create([
            'codigo_cie10' => 'C91.0',
            'nombre'       => 'Leucemia linfoblástica aguda',
            'descripcion'  => 'Tipo de cáncer de la sangre y la médula ósea que afecta a los glóbulos blancos.'
        ]);

        Enfermedad::create([
            'codigo_cie10' => 'D69.6',
            'nombre'       => 'Trombocitopenia no especificada',
            'descripcion'  => 'Disminución del recuento de plaquetas en el torrente sanguíneo.'
        ]);
    }
}