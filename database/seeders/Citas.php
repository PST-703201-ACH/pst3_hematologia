<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Cita;
use Carbon\Carbon;
use Faker\Factory as Faker;

class Citas extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');

        // Bancos de datos manuales en ESPAÑOL para evitar textos en latín
        $ciudadesVzla = ['Caracas', 'Maracay', 'Valencia', 'Barquisimeto', 'Maracaibo', 'San Cristóbal', 'Barcelona', 'Mérida'];
        $urbanizaciones = ['El Cafetal', 'Las Mercedes', 'Prebo', 'La Soledad', 'Los Caobos', 'Altamira', 'La Candelaria', 'Independencia'];
        $avenidasCalles = ['Av. Francisco de Miranda', 'Av. Bolívar', 'Calle Los Cerezos', 'Av. Intercomunal', 'Calle Las Flores', 'Av. Universidad'];

        $sintomasEs = [
            'Fiebre persistente y fatiga extrema.',
            'Palidez cutáneo-mucosa severa y debilidad.',
            'Aparición de hematomas espontáneos y petequias.',
            'Dolor óseo generalizado y pérdida de peso.',
            'Adenopatías cervicales palpables no dolorosas.',
            'Sangrado gingival frecuente al cepillarse.'
        ];

        $antecedentesPer = [
            'Ninguno de importancia hematológica.',
            'Asma bronquial controlado desde la infancia.',
            'Alergia conocida a la Penicilina.',
            'Infección respiratoria baja recurrente.',
            'Hospitalización previa por anemia leve.'
        ];

        $antecedentesFam = [
            'Madre con antecedente de hipertensión arterial.',
            'Abuelo paterno diagnosticado con Linfoma.',
            'Ningún antecedente onco-hematológico familiar.',
            'Padre con diabetes mellitus tipo 2.',
            'Historia familiar de trastornos de la coagulación.'
        ];

        $subjetivoEs = [
            'Paciente refiere sentirse con mayor energía pero persiste leve debilidad vespertina.',
            'Representante manifiesta mejoría en el apetito del paciente tras tratamiento inicial.',
            'Refiere episodios aislados de cefalea leve que ceden espontáneamente.',
            'Paciente manifiesta tolerancia adecuada a la dieta, sin náuseas ni vómitos.'
        ];

        $planEs = [
            'Continuar esquema actual. Solicitar Hematología Completa y frotis de sangre periférica.',
            'Mantener vigilancia clínica estricta. Control de laboratorios en dos semanas.',
            'Evaluación conjunta con servicio de nutrición. Cita de control programada.',
            'Repetir perfil hepático y renal antes de la próxima administración terapéutica.'
        ];

        $totalPacientes = 10;

        DB::transaction(function () use ($faker, $totalPacientes, $ciudadesVzla, $urbanizaciones, $avenidasCalles, $sintomasEs, $antecedentesPer, $antecedentesFam, $subjetivoEs, $planEs) {
            for ($i = 1; $i <= $totalPacientes; $i++) {
                
                $nombresPaciente = $faker->firstName . ' ' . $faker->firstName;
                $apellidosPaciente = $faker->lastName . ' ' . $faker->lastName;
                $cedula = 'V-' . $faker->numberBetween(10000000, 32000000);
                
                $nombresRep = $faker->firstNameMale;
                $apellidosRep = $faker->lastName . ' ' . $faker->lastName;

                // Generación de fecha de nacimiento pediátrica (entre 6 y 17 años de edad)
                $edadAleatoria = $faker->numberBetween(6, 17);
                $fechaNacimientoPediatrica = Carbon::now()->subYears($edadAleatoria)->subMonths($faker->numberBetween(0, 11))->subDays($faker->numberBetween(0, 30))->format('Y-m-d');

                $direccionCompleta = $faker->randomElement($avenidasCalles) . ', Casa Nro. ' . $faker->numberBetween(1, 99) . ', Urb. ' . $faker->randomElement($urbanizaciones) . ', ' . $faker->randomElement($ciudadesVzla);

                // Crear la entidad Persona con edad menor a 18 años
                $persona = Persona::create([
                    "nombres"          => $nombresPaciente,
                    "apellidos"        => $apellidosPaciente,
                    "fecha_nacimiento" => $fechaNacimientoPediatrica, // Edad ajustada aquí
                    "sexo"             => $faker->randomElement(['Masculino', 'Femenino']),
                    "cedula"           => $cedula,
                    "telefono"         => $faker->numerify('04##-#######'),
                    "email"            => $faker->unique()->safeEmail,
                    "estado_id"        => 1,
                    "municipio_id"     => 1,
                    "parroquia_id"     => 1,
                    "direccion_exacta" => $direccionCompleta,
                ]);

                $paciente = Paciente::create([
                    "persona_id" => $persona->persona_id,
                    "hc"         => $i,
                    "status"     => 1
                ]);

                $enfermedadId = \App\Models\Enfermedad::inRandomOrder()->first()?->enfermedad_id ?? $faker->numberBetween(1, 5);

                // =========================================================================
                // PASO A: PRIMERA CONSULTA (TIPO 1 - COMPLETA Y ASISTIDA)
                // =========================================================================
                $fechaPasada1 = Carbon::now()->subDays($faker->numberBetween(20, 40));
                $fechaPasada1->setTime($faker->numberBetween(8, 16), $faker->randomElement([0, 15, 30, 45]), 0);

                $consulta1 = Consulta::create([
                    "paciente_id"   => $paciente->paciente_id,
                    "medico_id"     => 1,
                    "tipo"          => 1,
                    "enfermedad_id" => $enfermedadId,
                    "fecha_hora"    => $fechaPasada1->format('Y-m-d H:i:s'),
                    "peso"          => $faker->randomFloat(2, 18, 55), // Pesos más lógicos para niños de 6 a 17 años
                    "talla"         => $faker->randomFloat(2, 110, 165), // Estaturas más lógicas para pediatría
                    "sc"            => $faker->randomFloat(2, 0.7, 1.6),
                    "fc"            => $faker->numberBetween(70, 110), // Frecuencia cardíaca pediátrica
                    "fr"            => $faker->numberBetween(16, 24), // Frecuencia respiratoria pediátrica
                    "ante_personal" => $faker->randomElement($antecedentesPer),
                    "ante_familiar" => $faker->randomElement($antecedentesFam),
                    "sintomas"      => $faker->randomElement($sintomasEs),
                    "subjetivo"    => $faker->randomElement($subjetivoEs),
                    "plan_trabajo" => $faker->randomElement($planEs),
                    "proxima_cita" => Carbon::now()->subDays(2)->format('Y-m-d'),
                    "status"        => 1,
                ]);

                Cita::create([
                    "nombres_paciente"        => $nombresPaciente,
                    "apellidos_paciente"      => $apellidosPaciente,
                    "nombres_representante"   => $nombresRep,
                    "apellidos_representante" => $apellidosRep,
                    "numero_hc"               => $i,
                    "fecha_hora"              => $fechaPasada1->format('Y-m-d H:i:s'),
                    "consulta_id"             => $consulta1->consulta_id
                ]);

                // =========================================================================
                // PASO B: SEGUNDA CONSULTA (TIPO 2 - CONTROL "LIMPIO" / SIN COMPLETAR)
                // =========================================================================
                if ($i <= 5) {
                    $fechaControl = Carbon::now()->subDays($faker->numberBetween(2, 5)); 
                    $statusControl = 0;
                } else {
                    $fechaControl = Carbon::now()->addDays($i - 5); 
                    $statusControl = 0;
                }
                
                $fechaControl->setTime($faker->numberBetween(8, 16), $faker->randomElement([0, 15, 30, 45]), 0);

                $consulta2 = Consulta::create([
                    "paciente_id"   => $paciente->paciente_id,
                    "medico_id"     => 1,
                    "tipo"          => 2,
                    "enfermedad_id" => $enfermedadId,
                    "fecha_hora"    => $fechaControl->format('Y-m-d H:i:s'),
                    "status"        => $statusControl,
                    
                    "peso"          => null,
                    "talla"         => null,
                    "sc"            => null,
                    "fc"            => null,
                    "fr"            => null,
                    "ante_personal" => null,
                    "ante_familiar" => null,
                    "sintomas"      => null,
                    "subjetivo"    => null,
                    "plan_trabajo" => null,
                    "proxima_cita" => null,
                ]);

                Cita::create([
                    "nombres_paciente"        => $nombresPaciente,
                    "apellidos_paciente"      => $apellidosPaciente,
                    "nombres_representante"   => $nombresRep,
                    "apellidos_representante" => $apellidosRep,
                    "numero_hc"               => $i,
                    "fecha_hora"              => $fechaControl->format('Y-m-d H:i:s'),
                    "consulta_id"             => $consulta2->consulta_id
                ]);
            }
        });
    }
}
                    
