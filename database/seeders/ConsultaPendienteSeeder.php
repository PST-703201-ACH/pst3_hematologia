<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultaPendienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorPersonaId = DB::table('persona')->where('cedula', '10000003')->value('persona_id');
        if (! $doctorPersonaId) {
            $doctorPersonaId = (DB::table('persona')->max('persona_id') ?? 0) + 1;
            DB::table('persona')->insert([
                'persona_id' => $doctorPersonaId,
                'nombres' => 'María',
                'apellidos' => 'Pérez',
                'fecha_nacimiento' => '1990-01-15',
                'sexo' => 'F',
                'cedula' => '10000003',
                'telefono' => '04140000003',
                'email' => 'maria.perez@example.com',
                'estado_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_exacta' => 'San Bernardino, Caracas',
            ]);
        }

        $doctorUsuarioId = DB::table('usuario')->where('username', '10000003')->value('usuario_id');
        if (! $doctorUsuarioId) {
            $doctorUsuarioId = (DB::table('usuario')->max('usuario_id') ?? 0) + 1;
            DB::table('usuario')->insert([
                'usuario_id' => $doctorUsuarioId,
                'persona_id' => $doctorPersonaId,
                'username' => '10000003',
                'password_hash' => bcrypt('Password123!'),
                'status' => 1,
                'id_rol' => 3,
            ]);
        }

        DB::table('tipo_consulta')->updateOrInsert(
            ['tipo_id' => 1],
            ['nombre' => 'Primera consulta']
        );
        DB::table('tipo_consulta')->updateOrInsert(
            ['tipo_id' => 2],
            ['nombre' => 'Control']
        );

        DB::table('enfermedad')->updateOrInsert(
            ['enfermedad_id' => 1],
            ['tipo' => true, 'descripcion' => 'Anemia falciforme']
        );
        DB::table('enfermedad')->updateOrInsert(
            ['enfermedad_id' => 2],
            ['tipo' => false, 'descripcion' => 'Leucemia linfoblastica aguda']
        );

        $this->crearConsultaPendientePrimeraVez($doctorUsuarioId);
        $this->crearConsultaPendienteControl($doctorUsuarioId);
    }

    protected function crearConsultaPendientePrimeraVez(int $medicoId): void
    {
        $numeroHc = 'HC-1001';

        $consultaId = (DB::table('consulta')->max('consulta_id') ?? 0) + 1;
        DB::table('consulta')->insert([
            'consulta_id' => $consultaId,
            'paciente_id' => null,
            'medico_id' => $medicoId,
            'tipo_id' => 1,
            'enfermedad_id' => 1,
            'fecha_hora' => now(),
            'peso' => 18.6,
            'talla' => 104.5,
            'sc' => 0.88,
            'fc' => 112,
            'fr' => 26,
            'antecedentes_personales' => 'Sin antecedentes médicos relevantes. Madre refiere dieta regular y sueño adecuado.',
            'antecedentes_familiares' => 'Abuela materna con antecedentes de anemia leve.',
            'signos_sintomas_iniciales' => 'fatiga, palidez, mareo y somnolencia ocasional.',
            'subjetivo' => 'Paciente refiere cansancio y debilidad al caminar durante 2 semanas.',
            'plan_trabajo' => 'Solicitar hemograma completo y valorar perfil hematológico.',
            'proxima_cita' => now()->addDays(7)->toDateString(),
            'status' => 0,
        ]);

        $citaId = (DB::table('cita')->max('cita_id') ?? 0) + 1;
        DB::table('cita')->insert([
            'cita_id' => $citaId,
            'nombres_paciente' => 'Ariana',
            'apellidos_paciente' => 'Torres',
            'nombres_representante' => 'Miriam',
            'apellidos_representante' => 'Torres',
            'numero_hc' => $numeroHc,
            'fecha_hora' => now()->addHour(),
            'consulta_id' => $consultaId,
            'estatus' => 'pendiente',
        ]);
    }

    protected function crearConsultaPendienteControl(int $medicoId): void
    {
        $numeroHc = 'HC-1002';

        $personaId = DB::table('persona')->where('cedula', '20000002')->value('persona_id');
        if (! $personaId) {
            $personaId = (DB::table('persona')->max('persona_id') ?? 0) + 1;
            DB::table('persona')->insert([
                'persona_id' => $personaId,
                'nombres' => 'Daniel',
                'apellidos' => 'Rivas',
                'fecha_nacimiento' => '2010-04-12',
                'sexo' => 'M',
                'cedula' => '20000002',
                'telefono' => '04140000002',
                'email' => 'daniel.rivas@example.com',
                'estado_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_exacta' => 'La Candelaria, Caracas',
            ]);
        }

        $pacienteId = DB::table('paciente')->where('hc', $numeroHc)->value('paciente_id');
        if (! $pacienteId) {
            $pacienteId = (DB::table('paciente')->max('paciente_id') ?? 0) + 1;
            DB::table('paciente')->insert([
                'paciente_id' => $pacienteId,
                'persona_id' => $personaId,
                'hc' => $numeroHc,
                'status' => 1,
            ]);
        }

        $consultaPrevId = DB::table('consulta')->where('paciente_id', $pacienteId)->where('status', 1)->value('consulta_id');
        if (! $consultaPrevId) {
            $consultaPrevId = (DB::table('consulta')->max('consulta_id') ?? 0) + 1;
            DB::table('consulta')->insert([
                'consulta_id' => $consultaPrevId,
                'paciente_id' => $pacienteId,
                'medico_id' => $medicoId,
                'tipo_id' => 1,
                'enfermedad_id' => 2,
                'fecha_hora' => now()->subDays(20),
                'peso' => 24.5,
                'talla' => 118.0,
                'sc' => 0.94,
                'fc' => 98,
                'fr' => 22,
                'subjetivo' => 'Primera valoración con síntomas leves y seguimiento de laboratorio inicial.',
                'plan_trabajo' => 'Monitoreo y protocolo inicial.',
                'proxima_cita' => now()->subDays(2)->toDateString(),
                'status' => 1,
            ]);
        }

        $consultaId = (DB::table('consulta')->max('consulta_id') ?? 0) + 1;
        DB::table('consulta')->insert([
            'consulta_id' => $consultaId,
            'paciente_id' => $pacienteId,
            'medico_id' => $medicoId,
            'tipo_id' => 2,
            'enfermedad_id' => 2,
            'fecha_hora' => now(),
            'peso' => 25.1,
            'talla' => 119.0,
            'sc' => 0.96,
            'fc' => 96,
            'fr' => 20,
            'subjetivo' => 'Paciente refiere mejoría en la energía, mantiene leve fatiga al final del día.',
            'plan_trabajo' => 'Continuar con protocolo, revisar resultados de laboratorio y controlar evolución.',
            'proxima_cita' => now()->addDays(10)->toDateString(),
            'status' => 0,
        ]);

        $citaId = (DB::table('cita')->max('cita_id') ?? 0) + 1;
        DB::table('cita')->insert([
            'cita_id' => $citaId,
            'nombres_paciente' => 'Daniel',
            'apellidos_paciente' => 'Rivas',
            'nombres_representante' => 'Laura',
            'apellidos_representante' => 'Rivas',
            'numero_hc' => $numeroHc,
            'fecha_hora' => now()->addHours(2),
            'consulta_id' => $consultaId,
            'estatus' => 'pendiente',
        ]);
    }
}
