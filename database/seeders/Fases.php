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
            'fase_id' => 1,
            'numero' => 1
        ]);

        Fase::create([
            'fase_id' => 2,
            'numero' => 2
        ]);

        Fase::create([
            'fase_id' => 3,
            'numero' => 3
        ]);

        Fase::create([
            'fase_id' => 4,
            'numero' => 4
        ]);
    }
}
