<?php

use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('loads the medical consultation form from a pending appointment', function () {
    DB::table('estado')->insert(['estado_id' => 1, 'nombre' => 'Distrito Capital']);
    DB::table('municipio')->insert(['municipio_id' => 1, 'estado_id' => 1, 'nombre' => 'Libertador']);
    DB::table('parroquia')->insert(['parroquia_id' => 1, 'municipio_id' => 1, 'nombre' => 'San Bernardino']);
    DB::table('rol')->insert(['rol_id' => 2, 'nombre' => 'Medico']);
    DB::table('tipo_consulta')->insert(['tipo_id' => 1, 'nombre' => 'Primera Consulta']);
    DB::table('enfermedad')->insert(['enfermedad_id' => 1, 'tipo' => true, 'descripcion' => 'Anemia Falciforme']);

    $persona = Persona::create([
        'nombres' => 'María',
        'apellidos' => 'García',
        'cedula' => '20000001',
        'telefono' => '04140000001',
        'email' => 'maria.test@example.com',
        'estado_id' => 1,
        'municipio_id' => 1,
        'parroquia_id' => 1,
    ]);

    $paciente = Paciente::create([
        'persona_id' => $persona->persona_id,
        'hc' => 'HC-900001',
        'status' => 1,
    ]);

    $usuarioId = DB::table('usuario')->insertGetId([
        'persona_id' => $persona->persona_id,
        'username' => 'medico.test',
        'password_hash' => bcrypt('Password123!'),
        'status' => 1,
        'id_rol' => 2,
    ]);

    $consulta = Consulta::create([
        'paciente_id' => $paciente->paciente_id,
        'medico_id' => $usuarioId,
        'tipo_id' => 1,
        'enfermedad_id' => 1,
        'fecha_hora' => now(),
        'status' => 0,
    ]);

    Cita::create([
        'nombres_paciente' => 'María',
        'apellidos_paciente' => 'García',
        'nombres_representante' => 'Pedro',
        'apellidos_representante' => 'García',
        'numero_hc' => 'HC-900001',
        'fecha_hora' => now(),
        'consulta_id' => $consulta->consulta_id,
        'estatus' => 'pendiente',
    ]);

    $usuario = User::find($usuarioId);

    $response = $this->actingAs($usuario)
        ->get('/medico/consultas/atender/' . Cita::first()->cita_id);

    $response->assertOk();
    $response->assertSee('Consulta Médica');
});
