<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class MedicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Asegurar que existe el rol de Médico
        $rol = Rol::updateOrCreate(
            ['rol_id' => 2],
            ['nombre' => 'Médico', 'status' => 'Activo']
        );

        // 2. Crear una Persona para el médico
        $persona = Persona::updateOrCreate(
            ['cedula' => '12345678'],
            [
                'persona_id' => 2,
                'nombres' => 'Gregory',
                'apellidos' => 'House',
                'fecha_nacimiento' => '1959-05-15',
                'sexo' => 'Masculino',
                'telefono' => '04121234567',
                'email' => 'house@hospital.com',
                'direccion_exacta' => 'Consultorio 42, Piso 2',
                'status' => 'Activo'
            ]
        );

        // 3. Crear el Usuario vinculado
        User::updateOrCreate(
            ['username' => 'medico'],
            [
                'usuario_id' => 2,
                'persona_id' => $persona->persona_id,
                'password_hash' => Hash::make('medico123'),
                'id_rol' => $rol->rol_id,
                'status' => User::STATUS_ACTIVO
            ]
        );
    }
}
