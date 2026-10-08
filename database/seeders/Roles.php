<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class Roles extends Seeder
{
    public function run(): void
    {
        // Roles existentes en el sistema

        // Administrador
        Rol::create([
            'nombre' => 'Administrador'
        ]);

        // Administrativo
        Rol::create([
            'nombre' => 'Administrativo'
        ]);

        // Medico
        Rol::create([
            'nombre' => 'Medico'
        ]);

        // Enfermero
        Rol::create([
            'nombre' => 'Enfermero'
        ]);
    }
}
