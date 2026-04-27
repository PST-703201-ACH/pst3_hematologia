<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Asegurar que existe el rol de Superadmin
        $rol = Rol::updateOrCreate(
            ['rol_id' => 1],
            ['nombre' => 'Superadmin', 'status' => 'Activo']
        );

        // 2. Crear una Persona para el usuario
        $persona = Persona::updateOrCreate(
            ['persona_id' => 1],
            [
                'nombres' => 'Admin',
                'apellidos' => 'Sistema',
                'cedula' => 'V-00000000',
                'fecha_nacimiento' => '1990-01-01',
                'sexo' => 'Masculino',
                'telefono' => '04120000000',
                'email' => 'admin@admin.com',
                'direccion_exacta' => 'Sede Principal',
                'status' => 'Activo'
            ]
        );

        // 3. Crear el Usuario vinculado
        User::updateOrCreate(
            ['usuario_id' => 1],
            [
                'username' => 'admin',
                'persona_id' => $persona->persona_id,
                'password_hash' => Hash::make('admin123'),
                'id_rol' => $rol->rol_id,
                'status' => 'Activo'
            ]
        );
    }
}
