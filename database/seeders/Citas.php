<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Cita;

class Citas extends Seeder
{
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $persona1 = Persona::create([
                "nombres" => "Jose David",
                "apellidos" => "Martinez Agrinzones",
                "cedula" => "V-12356983"
            ]);

            $paciente1 = Paciente::create([
                "persona_id" => $persona1->persona_id,
                "hc" => 1,
                "status" => 1
            ]);

            $consulta1 = Consulta::create([
                "paciente_id" => $paciente1->paciente_id,
                "tipo" => 1,
                "status" => 0
            ]);

            $fechaHora1 = new \DateTime();
            $fechaHora1Bd = $fechaHora1->format('Y-m-d H:i:s');


            Cita::create([
                "nombres_paciente" => "Jose David",
                "apellidos_paciente" => "Martinez Agrinzones",
                "nombres_representante" => "Josue Guillermo",
                "apellidos_representante" => "Martinez Carrillo",
                "numero_hc" => 1,
                "fecha_hora" => $fechaHora1Bd,
                "consulta_id" => $consulta1->consulta_id
            ]);

            $persona2 = Persona::create([
                "nombres" => "Juan Jose",
                "apellidos" => "Garcia Bohada",
                "cedula" => "V-12366983"
            ]);

            $paciente2 = Paciente::create([
                "persona_id" => $persona2->persona_id,
                "hc" => 2,
                "status" => 1
            ]);

            $consulta2 = Consulta::create([
                "paciente_id" => $paciente2->paciente_id,
                "tipo" => 1,
                "status" => 0
            ]);

            $fechaHora2 = new \DateTime();
            $fechaHora2Bd = $fechaHora2->format('Y-m-d H:i:s');


            Cita::create([
                "nombres_paciente" => "Juan Jose",
                "apellidos_paciente" => "Garcia Bohada",
                "nombres_representante" => "Jose Maria",
                "apellidos_representante" => "Bohada Yanez",
                "numero_hc" => 2,
                "fecha_hora" => $fechaHora2Bd,
                "consulta_id" => $consulta2->consulta_id
            ]);

            $persona3 = Persona::create([
                "nombres" => "Hector Haniel",
                "apellidos" => "Martinez Castillo",
                "cedula" => "V-12376983"
            ]);

            $paciente3 = Paciente::create([
                "persona_id" => $persona3->persona_id,
                "hc" => 3,
                "status" => 1
            ]);

            $consulta3 = Consulta::create([
                "paciente_id" => $paciente3->paciente_id,
                "tipo" => 1,
                "status" => 0
            ]);

            $fechaHora3 = new \DateTime();
            $fechaHora3Bd = $fechaHora3->format('Y-m-d H:i:s');


            Cita::create([
                "nombres_paciente" => "Hector Haniel",
                "apellidos_paciente" => "Martinez Castillo",
                "nombres_representante" => "Josue David",
                "apellidos_representante" => "Castillo Rodriguez",
                "numero_hc" => 3,
                "fecha_hora" => $fechaHora3Bd,
                "consulta_id" => $consulta3->consulta_id
            ]);

            $persona4 = Persona::create([
                "nombres" => "Sebastian Angel",
                "apellidos" => "Mendoza Parra",
                "cedula" => "V-12396983"
            ]);

            $paciente4 = Paciente::create([
                "persona_id" => $persona4->persona_id,
                "hc" => 4,
                "status" => 1
            ]);

            $consulta3 = Consulta::create([
                "paciente_id" => $paciente4->paciente_id,
                "tipo" => 1,
                "status" => 0
            ]);

            $fechaHora4 = new \DateTime();
            $fechaHora4Bd = $fechaHora4->format('Y-m-d H:i:s');


            Cita::create([
                "nombres_paciente" => "Sebastian Angel",
                "apellidos_paciente" => "Mendoza Parra",
                "nombres_representante" => "Juan David",
                "apellidos_representante" => "Mendoza Garcia",
                "numero_hc" => 4,
                "fecha_hora" => $fechaHora3Bd,
                "consulta_id" => $consulta3->consulta_id
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
