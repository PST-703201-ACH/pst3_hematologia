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
            'tipo' => 2,
            'descripcion' => 'Leucemia mieloide crónica',
        ]);

        Enfermedad::create([
            'tipo' => 2,
            'descripcion' => 'Linfoma de Hodgkin',
        ]);

        // Enfermedades Benignas
        Enfermedad::create([
            'tipo' => 1,
            'descripcion' => 'Anemia ferropénica',
        ]);

        Enfermedad::create([
            'tipo' => 1,
            'descripcion' => 'Hemofilia',
        ]);

        Enfermedad::create([
            'tipo' => 1,
            'descripcion' => 'Trombocitopenia inmune',
        ]);

    }
}