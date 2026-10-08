<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fase;

class Fases extends Seeder
{
    public function run(): void
    {
        // Fases de protocolo
        Fase::create([
            'numero' => 1
        ]);

        Fase::create([
            'numero' => 2
        ]);

        Fase::create([
            'numero' => 3
        ]);

        Fase::create([
            'numero' => 4
        ]);
    }
}
