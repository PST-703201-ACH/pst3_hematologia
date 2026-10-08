<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estado;

class Estados extends Seeder
{
    public function run(): void
    {
        Estado::create([
            'nombre' => 'Distrito Capital'
        ]);

        // Administrativo
        Estado::create([
            'nombre' => 'Miranda'
        ]);
    }
}
