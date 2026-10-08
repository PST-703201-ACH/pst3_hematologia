<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicina;

class Medicinas extends Seeder
{
    public function run(): void
    {
        Medicina::create([
            'descripcion' => 'Sulfato ferroso'
        ]);

        Medicina::create([
            'descripcion' => 'Imatinib'
        ]);

        Medicina::create([
            'descripcion' => 'Factor VIII recombinante'
        ]);

        Medicina::create([
            'descripcion' => 'Rituximab'
        ]);

        Medicina::create([
            'descripcion' => 'Eritropoyetina'
        ]);
    }
}
