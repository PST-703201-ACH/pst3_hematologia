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
            'medicina_id' => 1,
            'descripcion' => 'Sulfato ferroso'
        ]);

        Medicina::create([
            'medicina_id' => 2,
            'descripcion' => 'Imatinib'
        ]);

        Medicina::create([
            'medicina_id' => 3,
            'descripcion' => 'Factor VIII recombinante'
        ]);

        Medicina::create([
            'medicina_id' => 4,
            'descripcion' => 'Rituximab'
        ]);

        Medicina::create([
            'medicina_id' => 5,
            'descripcion' => 'Eritropoyetina'
        ]);
    }
}
