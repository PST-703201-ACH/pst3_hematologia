<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Examen;
use App\Models\TipoConsulta;

class CatalogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Instanciamos los Roles del Sistema Clínico
        Rol::create(['nombre' => 'Administrador', 'descripcion' => 'Control total del sistema']);
        Rol::create(['nombre' => 'Bioanalista', 'descripcion' => 'Encargado de procesar muestras y resultados de hematología']);
        Rol::create(['nombre' => 'Recepcionista', 'descripcion' => 'Encargado de registrar pacientes y gestionar órdenes']);

        // 2. Instanciamos los Exámenes Clínicos Base
        Examen::create([
            'codigo_examen' => 'HEM01',
            'nombre' => 'Hemoglobina',
            'unidad_medida' => 'g/dL',
            'valor_minimo_referencia' => 12.00,
            'valor_maximo_referencia' => 16.00,
            'indicaciones' => 'Ayuno obligatorio de 8 horas'
        ]);

        Examen::create([
            'codigo_examen' => 'PLA02',
            'nombre' => 'Plaquetas',
            'unidad_medida' => 'mm3',
            'valor_minimo_referencia' => 150000.00,
            'valor_maximo_referencia' => 450000.00,
            'indicaciones' => 'No requiere preparación especial'
        ]);

        // 3. Instanciamos los Tipos de Consulta
        TipoConsulta::create(['nombre' => 'Primera Vez']);
        TipoConsulta::create(['nombre' => 'Sucesiva']);
        TipoConsulta::create(['nombre' => 'Emergencia']);
    }
}