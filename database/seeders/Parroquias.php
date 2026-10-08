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
            'nombre' => 'San Bernardino'
        ]);

        Parroquia::create([
            'nombre' => 'La Candelaria'
        ]);

        Parroquia::create([
            'nombre' => 'Chacao'
        ]);
    }
}