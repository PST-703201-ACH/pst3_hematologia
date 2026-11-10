<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Protocolo;
use App\Models\Fase;
use App\Models\Enfermedad;
use App\Models\Medicina;
use App\Models\MedicinaProt;

class CatalogoMedico extends Seeder
{

    public function run(): void
    {
        $protPola = Protocolo::create([
            'nombre' => 'POLA'
        ]);

        $fase1Pola = Fase::create([
            'numero' => 1
        ]);

        $medicina1 = Medicina::create([
            'descripcion' => 'Prednisona'
        ]);

        $medicina2 = Medicina::create([
            'descripcion' => 'Ciclofosfamida'
        ]);

        $medicina3 = Medicina::create([
            'descripcion' => 'Vincristina'
        ]);

        $medicina4 = Medicina::create([
            'descripcion' => 'Daunorrubicina'
        ]);

        $medicina5 = Medicina::create([
            'descripcion' => 'L-asparaginasa'
        ]);

        $medicina6 = Medicina::create([
            'descripcion' => 'Citarabina'
        ]);

        $medicina7 = Medicina::create([
            'descripcion' => 'Mercaptopurina'
        ]);

        $medicina8 = Medicina::create([
            'descripcion' => 'Metotrexato'
        ]);

        $fase2Pola = Fase::create([
            'numero' => 2
        ]);

        $fase3Pola = Fase::create([
            'numero' => 3
        ]);

        $fase4Pola = Fase::create([
            'numero' => 4
        ]);

        // Fase 1
        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina1->medicina_id, 
            'id_fase' => $fase1Pola->fase_id, 
            'via_admin' => 3
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina2->medicina_id, 
            'id_fase' => $fase1Pola->fase_id, 
            'via_admin' => 2
        ]);

        // Fase 2
        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina3->medicina_id, 
            'id_fase' => $fase2Pola->fase_id, 
            'via_admin' => 2
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina4->medicina_id, 
            'id_fase' => $fase2Pola->fase_id, 
            'via_admin' => 2
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina5->medicina_id, 
            'id_fase' => $fase2Pola->fase_id, 
            'via_admin' => 1
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina1->medicina_id, 
            'id_fase' => $fase2Pola->fase_id, 
            'via_admin' => 3
        ]);

        // Fase 3
        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina6->medicina_id, 
            'id_fase' => $fase3Pola->fase_id, 
            'via_admin' => 2
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina8->medicina_id, 
            'id_fase' => $fase3Pola->fase_id, 
            'via_admin' => 2
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina7->medicina_id, 
            'id_fase' => $fase3Pola->fase_id, 
            'via_admin' => 3
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina3->medicina_id, 
            'id_fase' => $fase3Pola->fase_id, 
            'via_admin' => 2
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina5->medicina_id, 
            'id_fase' => $fase3Pola->fase_id, 
            'via_admin' => 1
        ]);

        // Fase 4
        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina7->medicina_id, 
            'id_fase' => $fase4Pola->fase_id, 
            'via_admin' => 3
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina8->medicina_id, 
            'id_fase' => $fase4Pola->fase_id, 
            'via_admin' => 3
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina3->medicina_id, 
            'id_fase' => $fase4Pola->fase_id, 
            'via_admin' => 2
        ]);

        MedicinaProt::create([
            'id_protocolo' => $protPola->protocolo_id, 
            'id_medicina' => $medicina1->medicina_id, 
            'id_fase' => $fase4Pola->fase_id, 
            'via_admin' => 3
        ]);

        Enfermedad::create([
            'tipo' => 1,
            'descripcion' => 'Anemia Ferropenica'
        ]);

        Enfermedad::create([
            'tipo' => 1,
            'descripcion' => 'Anemia Megaloblastica'
        ]);

        Enfermedad::create([
            'tipo' => 1,
            'descripcion' => 'Anemia Hemolitica'
        ]);

        Enfermedad::create([
            'tipo' => 1,
            'descripcion' => 'Anemia Aplasica'
        ]);

        Enfermedad::create([
            'tipo' => 2,
            'descripcion' => 'Leucemia Linfoblastica Aguda'
        ]);

        Enfermedad::create([
            'tipo' => 2,
            'descripcion' => 'Leucemia Mieloide Aguda'
        ]);

        Enfermedad::create([
            'tipo' => 2,
            'descripcion' => 'Leucemia Linfatica Cronica'
        ]);

        Enfermedad::create([
            'tipo' => 2,
            'descripcion' => 'Leucemia Mieloide Cronica'
        ]);

    }
}
