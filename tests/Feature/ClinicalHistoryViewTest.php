<?php

use Illuminate\Support\Facades\DB;

it('keeps the clinical history entry in the medical panel, not the administrator panel', function () {
    $doctorLayout = file_get_contents(resource_path('views/medico/index.blade.php'));
    $adminLayout = file_get_contents(resource_path('views/administrador/index.blade.php'));
    $adminSidebar = file_get_contents(resource_path('views/partials/sidebar_admin.blade.php'));
    $doctorSidebar = file_get_contents(resource_path('views/partials/sidebar_med.blade.php'));
    $adminButtons = file_get_contents(public_path('assets/js/botones_admin.js'));

    expect($doctorLayout)
        ->toContain("@include('administrador.historia_clinica')")
        ->toContain('assets/js/historia-clinica-controller.js')
        ->toContain('assets/js/historia-clinica-templates.js');
    expect($doctorSidebar)->toContain('id="btnHistoriaClinicaMed"');
    expect($adminLayout)
        ->not->toContain("@include('administrador.historia_clinica')")
        ->not->toContain('historia-clinica-controller.js');
    expect($adminSidebar)
        ->not->toContain('btnHistoriaClinica')
        ->not->toContain('Historia Clínica');
    expect($adminButtons)->not->toContain('btnHistoriaClinica');

    expect(file_get_contents(public_path('assets/js/historia-clinica-controller.js')))
        ->toContain('fetch')
        ->toContain('PST3ClinicalHistoryTemplates');
    expect(file_get_contents(public_path('assets/js/historia-clinica-templates.js')))
        ->toContain('PST3ClinicalHistoryTemplates')
        ->not->toContain('desechable')
        ->not->toContain('laboratorios')
        ->not->toContain('Transfusiones');
});

it('returns patient history from primary tables without requiring optional or disposable schemas', function () {
    DB::statement("ATTACH DATABASE ':memory:' AS logistica");

    foreach ([
        'CREATE TABLE persona (persona_id INTEGER PRIMARY KEY, nombres TEXT, apellidos TEXT, fecha_nacimiento TEXT, sexo TEXT, cedula TEXT, telefono TEXT, email TEXT, estado_id INTEGER, municipio_id INTEGER, parroquia_id INTEGER, direccion_exacta TEXT)',
        'CREATE TABLE estado (estado_id INTEGER PRIMARY KEY, nombre TEXT)',
        'CREATE TABLE municipio (municipio_id INTEGER PRIMARY KEY, nombre TEXT)',
        'CREATE TABLE parroquia (parroquia_id INTEGER PRIMARY KEY, nombre TEXT)',
        'CREATE TABLE logistica.paciente (paciente_id INTEGER PRIMARY KEY, persona_id INTEGER, hc INTEGER, status INTEGER)',
        'CREATE TABLE logistica.consulta (consulta_id INTEGER PRIMARY KEY, paciente_id INTEGER, medico_id INTEGER, tipo INTEGER, enfermedad_id INTEGER, fecha_hora TEXT, peso TEXT, talla TEXT, sc TEXT, fc TEXT, fr TEXT, ante_personal TEXT, ante_familiar TEXT, sintomas TEXT, subjetivo TEXT, plan_trabajo TEXT, proxima_cita TEXT, status INTEGER)',
        'CREATE TABLE logistica.enfermedad (enfermedad_id INTEGER PRIMARY KEY, tipo INTEGER, descripcion TEXT)',
    ] as $statement) {
        DB::statement($statement);
    }

    DB::table('persona')->insert([
        'persona_id' => 1,
        'nombres' => 'María',
        'apellidos' => 'García',
        'fecha_nacimiento' => '2010-03-04',
        'sexo' => 'Femenino',
        'cedula' => '12345678',
        'telefono' => '04140000000',
        'email' => 'maria@example.test',
        'estado_id' => 1,
        'municipio_id' => 1,
        'parroquia_id' => 1,
        'direccion_exacta' => 'Dirección',
    ]);
    DB::table('estado')->insert(['estado_id' => 1, 'nombre' => 'Distrito Capital']);
    DB::table('municipio')->insert(['municipio_id' => 1, 'nombre' => 'Libertador']);
    DB::table('parroquia')->insert(['parroquia_id' => 1, 'nombre' => 'San Bernardino']);
    DB::table('logistica.paciente')->insert(['paciente_id' => 7, 'persona_id' => 1, 'hc' => 2345, 'status' => 1]);
    DB::table('logistica.enfermedad')->insert(['enfermedad_id' => 2, 'descripcion' => 'Anemia']);
    DB::table('logistica.consulta')->insert([
        'consulta_id' => 9,
        'paciente_id' => 7,
        'tipo' => 1,
        'enfermedad_id' => 2,
        'fecha_hora' => '2026-10-28 10:00:00',
        'peso' => '42',
        'sintomas' => 'Fatiga',
        'plan_trabajo' => 'Control',
        'status' => 1,
    ]);

    $doctor = new \App\Models\User();
    $doctor->forceFill(['usuario_id' => 3, 'id_rol' => 3]);
    $this->actingAs($doctor)
        ->get(route('medico.historia-clinica.pacientes', ['q' => 'mArÍa']))
        ->assertOk()
        ->assertJsonPath('data.0.paciente_id', 7)
        ->assertJsonPath('data.0.nombre', 'María García')
        ->assertJsonPath('data.0.hc', 2345)
        ->assertJsonPath('total', 1);

    $this->get(route('medico.historia-clinica.detalle', ['pacienteId' => 7]))
        ->assertOk()
        ->assertJsonPath('paciente.persona.estado', 'Distrito Capital')
        ->assertJsonPath('consultas.0.enfermedad', 'Anemia')
        ->assertJsonPath('consultas.0.peso', '42')
        ->assertJsonPath('consultas.0.sintomas', 'Fatiga')
        ->assertJsonMissingPath('representantes')
        ->assertJsonMissingPath('laboratorios');

    $this->get('/admin/historias-clinicas/pacientes')->assertNotFound();
});
