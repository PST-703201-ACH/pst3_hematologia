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
            'rol_id' => 1,
            'nombre' => 'Administrador'
        ]);

        // Administrativo
        Rol::create([
            'rol_id' => 2,
            'nombre' => 'Administrativo'
        ]);

        // Medico
        Rol::create([
            'rol_id' => 3,
            'nombre' => 'Medico'
        ]);

        // Enfermero
        Rol::create([
            'rol_id' => 4,
            'nombre' => 'Enfermero'
        ]);
    }
}
