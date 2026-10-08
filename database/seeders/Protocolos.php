<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Protocolo;
use App\Models\MedicinaProt;

class Protocolos extends Seeder
{
    public function run(): void
    {
        Protocolo::create([
            'nombre' => 'Protocolo Estandar'
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 1,
            'id_fase' => 1,
            'via_admin' => 1,
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 2,
            'id_fase' => 1,
            'via_admin' => 2,
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 3,
            'id_fase' => 1,
            'via_admin' => 1,
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 4,
            'id_fase' => 1,
            'via_admin' => 3,
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 5,
            'id_fase' => 1,
            'via_admin' => 3,
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 1,
            'id_fase' => 2,
            'via_admin' => 2,
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 1,
            'id_fase' => 3,
            'via_admin' => 2,
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 2,
            'id_fase' => 3,
            'via_admin' => 1,
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 3,
            'id_fase' => 3,
            'via_admin' => 2,
        ]);

        MedicinaProt::create([
            'id_protocolo' => 1,
            'id_medicina' => 5,
            'id_fase' => 4,
            'via_admin' => 3,
        ]);
    }
}