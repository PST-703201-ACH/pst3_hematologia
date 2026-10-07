<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enfermedad;

class Enfermedades extends Seeder
{
    public function run(): void
    {
        // Enfermedades Malignas
        Enfermedad::create([
            'enfermedad_id' => 1,
            'tipo' => 2,
            'descripcion' => 'Leucemia mieloide crónica',
        ]);

        Enfermedad::create([
            'enfermedad_id' => 2,
            'tipo' => 2,
            'descripcion' => 'Linfoma de Hodgkin',
        ]);

        // Enfermedades Benignas
        Enfermedad::create([
            'enfermedad_id' => 3,
            'tipo' => 1,
            'descripcion' => 'Anemia ferropénica',
        ]);

        Enfermedad::create([
            'enfermedad_id' => 4,
            'tipo' => 1,
            'descripcion' => 'Hemofilia',
        ]);

        Enfermedad::create([
            'enfermedad_id' => 5,
            'tipo' => 1,
            'descripcion' => 'Trombocitopenia inmune',
        ]);

    }
}