<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parroquia;

class Parroquias extends Seeder
{
    public function run(): void
    {
        // Parroquias

        Parroquia::create([
            'parroquia_id' => 1,
            'nombre' => 'San Bernardino'
        ]);

        Parroquia::create([
            'parroquia_id' => 2,
            'nombre' => 'La Candelaria'
        ]);

        Parroquia::create([
            'parroquia_id' => 3,
            'nombre' => 'Chacao'
        ]);
    }
}