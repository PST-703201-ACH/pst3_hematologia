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
        $persona1 = Persona::create([
            'persona_id' => 1,
            'nombres' => 'Angel David',
            'apellidos' => 'Granados Carrillo',
            'fecha_nacimiento' => '2006-07-24',
            'sexo' => 'Masculino',
            'cedula' => 'V-31940105',
            'telefono' => '04241457972',
            'email' => 'granadoscarrilloangeldavid@gmail.com',
            'estado_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_exacta' => 'Las Adjuntas, la sosa'
        ]);

        $persona2 = Persona::create([
            'persona_id' => 2,
            'nombres' => 'Jeandel Jose',
            'apellidos' => 'Hernandez Muñoz',
            'fecha_nacimiento' => '2006-07-24',
            'sexo' => 'Masculino',
            'cedula' => 'V-31491413',
            'telefono' => '04125894503',
            'email' => 'jeandelhernandez@gmail.com',
            'estado_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_exacta' => 'Caricuao, Zoologico'
        ]);

        User::create([
            'usuario_id' => 1,
            'persona_id' => $persona1->persona_id,
            'username' => '31940105',
            'password_hash' => Hash::make('123456'),
            'status' => 1,
            'id_rol' => 1
        ]);

        User::create([
            'usuario_id' => 2,
            'persona_id' => $persona2->persona_id,
            'username' => '31491413',
            'password_hash' => Hash::make('123456'),
            'status' => 1,
            'id_rol' => 2
        ]);
    }
}