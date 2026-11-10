<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;
use App\Models\User;

class Usuarios extends Seeder
{
    public function run(): void
    {
        // --- USUARIOS QUE YA TENÍAS EN TU CÓDIGO ---

        $persona1 = Persona::create([
            'nombres' => 'Rafael Garcia',
            'apellidos' => 'Montes Parra',
            'fecha_nacimiento' => '2006-07-24',
            'sexo' => 'Masculino',
            'cedula' => 'V-32980115',
            'telefono' => '04121457972',
            'email' => 'admin@yopmail.com',
            'estado_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_exacta' => 'Las Adjuntas, la sosa'
        ]);

        $persona2 = Persona::create([
            'nombres' => 'Moises David',
            'apellidos' => 'Castillo Gutierrez',
            'fecha_nacimiento' => '2006-07-24',
            'sexo' => 'Masculino',
            'cedula' => 'V-31492414',
            'telefono' => '04245894503',
            'email' => 'admvo@yopmail.com',
            'estado_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_exacta' => 'Caricuao, Zoologico'
        ]);

        User::create([
            'persona_id' => $persona1->persona_id,
            'username' => '32980115',
            'password_hash' => Hash::make('123456'),
            'status' => 1,
            'id_rol' => 3
        ]);

        User::create([
            'persona_id' => $persona2->persona_id,
            'username' => '31492414',
            'password_hash' => Hash::make('123456'),
            'status' => 1,
            'id_rol' => 2
        ]);


        // --- NUEVOS 4 USUARIOS AGREGADOS (ROLES DEL 1 AL 4) ---

        // Usuario Nuevo 1 - Rol 1
        $persona3 = Persona::create([
            'nombres' => 'Carlos Eduardo',
            'apellidos' => 'Mendoza Silva',
            'fecha_nacimiento' => '1995-03-12',
            'sexo' => 'Masculino',
            'cedula' => 'V-24156789',
            'telefono' => '04141234567',
            'email' => 'medico1@yopmail.com',
            'estado_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_exacta' => 'El Paraíso, Av. Páez'
        ]);

        User::create([
            'persona_id' => $persona3->persona_id,
            'username' => '24156789',
            'password_hash' => Hash::make('123456'),
            'status' => 1,
            'id_rol' => 1 // Asignado Rol 1
        ]);

        // Usuario Nuevo 2 - Rol 2
        $persona4 = Persona::create([
            'nombres' => 'Ana Karina',
            'apellidos' => 'Rojas Contreras',
            'fecha_nacimiento' => '1998-09-05',
            'sexo' => 'Femenino',
            'cedula' => 'V-26889432',
            'telefono' => '04169876543',
            'email' => 'recepcion@yopmail.com',
            'estado_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_exacta' => 'Chacao, Calle Elice'
        ]);

        User::create([
            'persona_id' => $persona4->persona_id,
            'username' => '26889432',
            'password_hash' => Hash::make('123456'),
            'status' => 1,
            'id_rol' => 2 // Asignado Rol 2
        ]);

        // Usuario Nuevo 3 - Rol 3
        $persona5 = Persona::create([
            'nombres' => 'Luis Alejandro',
            'apellidos' => 'Pérez Palacios',
            'fecha_nacimiento' => '1992-11-20',
            'sexo' => 'Masculino',
            'cedula' => 'V-20456112',
            'telefono' => '04241112233',
            'email' => 'supervisor@yopmail.com',
            'estado_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_exacta' => 'La California, Av. Trieste'
        ]);

        User::create([
            'persona_id' => $persona5->persona_id,
            'username' => '20456112',
            'password_hash' => Hash::make('123456'),
            'status' => 1,
            'id_rol' => 3 // Asignado Rol 3
        ]);

        // Usuario Nuevo 4 - Rol 4
        $persona6 = Persona::create([
            'nombres' => 'María Valentina',
            'apellidos' => 'Gómez Rangel',
            'fecha_nacimiento' => '2001-05-14',
            'sexo' => 'Femenino',
            'cedula' => 'V-28556994',
            'telefono' => '04125556677',
            'email' => 'director@yopmail.com',
            'estado_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_exacta' => 'Altamira, 5ta Transversal'
        ]);

        User::create([
            'persona_id' => $persona6->persona_id,
            'username' => '28556994',
            'password_hash' => Hash::make('123456'),
            'status' => 1,
            'id_rol' => 4 // Asignado Rol 4
        ]);
    }
}
