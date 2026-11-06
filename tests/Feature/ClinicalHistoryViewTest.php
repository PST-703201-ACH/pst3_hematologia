<?php

use Illuminate\Support\Facades\DB;

it('keeps the clinical history entry in the medical panel, not the administrator panel', function () {
    $doctorLayout = file_get_contents(resource_path('views/medico/index.blade.php'));
    $patientList = file_get_contents(resource_path('views/medico/pacientes/listado.blade.php'));
    $adminLayout = file_get_contents(resource_path('views/administrador/index.blade.php'));
    $adminSidebar = file_get_contents(resource_path('views/partials/sidebar_admin.blade.php'));
    $doctorSidebar = file_get_contents(resource_path('views/partials/sidebar_med.blade.php'));
    $adminButtons = file_get_contents(public_path('assets/js/botones_admin.js'));

    expect($doctorLayout)
        ->toContain("@include('medico.pacientes.historia_clinica')")
        ->toContain('assets/js/historia-clinica-controller.js')
        ->not->toContain('assets/js/historia-clinica-templates.js');
    expect($doctorSidebar)->toContain('id="btnHistoriaClinicaMed"');
    expect($patientList)
        ->toContain('js-open-clinical-history')
        ->toContain('data-paciente-id="{{ $paciente->paciente_id }}"')
        ->toContain('data-paciente-nombre="{{ $paciente->persona->nombres }} {{ $paciente->persona->apellidos }}"')
        ->toContain('fas fa-eye')
        ->not->toContain('medico.pacientes.show')
        ->not->toContain('Datos de Laboratorio')
        ->not->toContain('Actualizar Datos');
    expect($doctorLayout)
        ->not->toContain('seccion-paciente_detalle')
        ->not->toContain('listar_paciente.js');
    expect($adminLayout)
        ->not->toContain("@include('administrador.historia_clinica')")
        ->not->toContain('historia-clinica-controller.js');
    expect($adminSidebar)
        ->not->toContain('btnHistoriaClinica')
        ->not->toContain('Historia Clínica');
    expect($adminButtons)->not->toContain('btnHistoriaClinica');

    expect(file_get_contents(public_path('assets/js/historia-clinica-controller.js')))
        ->toContain('fetch')
        ->toContain("headers: { Accept: 'text/html' }")
        ->toContain('searchInput.value = patientName')
        ->toContain('loadPatients(searchInput.value)')
        ->toContain('detailContainer.innerHTML = detailHtml')
        ->toContain('data-consultation-item')
        ->not->toContain('PST3ClinicalHistoryTemplates');
    expect(file_get_contents(resource_path('views/medico/pacientes/detalle_historia.blade.php')))
        ->toContain('Protocolos asignados')
        ->toContain('Sin protocolos registrados')
        ->toContain('data-consultation-page')
        ->toContain('Página 1 de {{ $consultationPages }}')
        ->toContain('data-consultation-item')
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
    DB::table('persona')->insert([
        'persona_id' => 2,
        'nombres' => 'María',
        'apellidos' => 'López',
        'sexo' => 'Femenino',
    ]);
    DB::table('estado')->insert(['estado_id' => 1, 'nombre' => 'Distrito Capital']);
    DB::table('municipio')->insert(['municipio_id' => 1, 'nombre' => 'Libertador']);
    DB::table('parroquia')->insert(['parroquia_id' => 1, 'nombre' => 'San Bernardino']);
    DB::table('logistica.paciente')->insert(['paciente_id' => 7, 'persona_id' => 1, 'hc' => 2345, 'status' => 1]);
    DB::table('logistica.paciente')->insert(['paciente_id' => 8, 'persona_id' => 2, 'hc' => 2346, 'status' => 1]);
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
        ->get(route('medico.historia-clinica.pacientes', ['q' => 'María García']))
        ->assertOk()
        ->assertJsonPath('data.0.paciente_id', 7)
        ->assertJsonPath('data.0.nombre', 'María García')
        ->assertJsonPath('data.0.hc', 2345)
        ->assertJsonPath('total', 1);

    $this->get(route('medico.historia-clinica.pacientes', ['q' => 'María']))
        ->assertOk()
        ->assertJsonPath('total', 2);

    $this->get(route('medico.historia-clinica.pacientes'))
        ->assertOk()
        ->assertJsonPath('total', 2);

    $this->get(route('medico.historia-clinica.detalle', ['pacienteId' => 7]))
        ->assertOk()
        ->assertViewIs('medico.pacientes.detalle_historia')
        ->assertSee('María García')
        ->assertSee('Distrito Capital')
        ->assertSee('Anemia')
        ->assertSee('Fatiga')
        ->assertSee('Sin protocolos registrados')
        ->assertDontSee('representantes')
        ->assertDontSee('laboratorios');

    DB::statement('CREATE TABLE logistica.protocolo (protocolo_id INTEGER PRIMARY KEY, nombre TEXT NOT NULL)');
    DB::statement('CREATE TABLE logistica.medicina_trat (id INTEGER PRIMARY KEY, paciente_id INTEGER, consulta_id INTEGER, protocolo_id INTEGER)');
    DB::table('logistica.consulta')->insert([
        'consulta_id' => 10,
        'paciente_id' => 7,
        'tipo' => 2,
        'fecha_hora' => '2026-10-29 10:00:00',
        'status' => 1,
    ]);
    DB::table('logistica.protocolo')->insert([
        ['protocolo_id' => 20, 'nombre' => 'Protocolo Alfa'],
        ['protocolo_id' => 21, 'nombre' => 'Protocolo Beta'],
    ]);
    DB::table('logistica.medicina_trat')->insert([
        ['id' => 1, 'paciente_id' => 7, 'consulta_id' => 9, 'protocolo_id' => 20],
        ['id' => 2, 'paciente_id' => 7, 'consulta_id' => 9, 'protocolo_id' => 20],
        ['id' => 3, 'paciente_id' => 7, 'consulta_id' => 9, 'protocolo_id' => 21],
    ]);

    $this->get(route('medico.historia-clinica.detalle', ['pacienteId' => 7]))
        ->assertOk()
        ->assertViewIs('medico.pacientes.detalle_historia')
        ->assertSee('Protocolo Alfa')
        ->assertSee('Protocolo Beta')
        ->assertSee('data-consultation-item', false);

    $this->get('/admin/historias-clinicas/pacientes')->assertNotFound();
    $this->get('/medico/pacientes/7')->assertMethodNotAllowed();
});
