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
            'nombre' => 'Libertador'
        ]);

        Municipio::create([
            'nombre' => 'Chacao'
        ]);
    }
}