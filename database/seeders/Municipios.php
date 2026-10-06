<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Municipio;

class Municipios extends Seeder
{
    public function run(): void
    {
        // Municipios

        Municipio::create([
            'municipio_id' => 1,
            'nombre' => 'Libertador'
        ]);

        Municipio::create([
            'municipio_id' => 2,
            'nombre' => 'Chacao'
        ]);
    }
}