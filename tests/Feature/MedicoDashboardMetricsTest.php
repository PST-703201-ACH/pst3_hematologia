<?php

use App\Services\MedicoDashboardMetrics;
use Illuminate\Support\Facades\DB;

function createMedicoDashboardTables(): void
{
    DB::statement("ATTACH DATABASE ':memory:' AS logistica");

    foreach ([
        'CREATE TABLE persona (persona_id INTEGER PRIMARY KEY, sexo TEXT)',
        'CREATE TABLE logistica.paciente (paciente_id INTEGER PRIMARY KEY, persona_id INTEGER, hc INTEGER, status INTEGER)',
        'CREATE TABLE logistica.consulta (consulta_id INTEGER PRIMARY KEY, paciente_id INTEGER, tipo INTEGER, enfermedad_id INTEGER, status INTEGER)',
        'CREATE TABLE logistica.enfermedad (enfermedad_id INTEGER PRIMARY KEY, descripcion TEXT)',
        'CREATE TABLE logistica.tratamiento (tratamiento_id INTEGER PRIMARY KEY, status INTEGER)',
        'CREATE TABLE logistica.protocolo (protocolo_id INTEGER PRIMARY KEY, nombre TEXT)',
        'CREATE TABLE logistica.medicina (medicina_id INTEGER PRIMARY KEY, descripcion TEXT)',
        'CREATE TABLE logistica.medicina_trat (id INTEGER PRIMARY KEY, paciente_id INTEGER, consulta_id INTEGER, protocolo_id INTEGER, medicina_id INTEGER, tratamiento_id INTEGER)',
    ] as $statement) {
        DB::statement($statement);
    }
}

it('builds dashboard metrics from clinical records and active protocol assignments', function () {
    createMedicoDashboardTables();

    DB::table('persona')->insert([
        ['persona_id' => 1, 'sexo' => 'Femenino'],
        ['persona_id' => 2, 'sexo' => 'Masculino'],
        ['persona_id' => 3, 'sexo' => 'Femenino'],
        ['persona_id' => 4, 'sexo' => null],
    ]);
    DB::table('logistica.paciente')->insert([
        ['paciente_id' => 1, 'persona_id' => 1, 'hc' => 101, 'status' => 1],
        ['paciente_id' => 2, 'persona_id' => 2, 'hc' => 102, 'status' => 1],
        ['paciente_id' => 3, 'persona_id' => 3, 'hc' => 103, 'status' => 1],
        ['paciente_id' => 4, 'persona_id' => 4, 'hc' => 104, 'status' => 1],
    ]);
    DB::table('logistica.enfermedad')->insert([
        ['enfermedad_id' => 1, 'descripcion' => 'Anemia'],
        ['enfermedad_id' => 2, 'descripcion' => 'Leucemia'],
    ]);
    DB::table('logistica.consulta')->insert([
        ['consulta_id' => 1, 'paciente_id' => 1, 'tipo' => 1, 'enfermedad_id' => 1, 'status' => 1],
        ['consulta_id' => 2, 'paciente_id' => 1, 'tipo' => 2, 'enfermedad_id' => 1, 'status' => 1],
        ['consulta_id' => 3, 'paciente_id' => 2, 'tipo' => 2, 'enfermedad_id' => 1, 'status' => 1],
        ['consulta_id' => 4, 'paciente_id' => 3, 'tipo' => 2, 'enfermedad_id' => 2, 'status' => 0],
        ['consulta_id' => 5, 'paciente_id' => 3, 'tipo' => 1, 'enfermedad_id' => 2, 'status' => 1],
    ]);
    DB::table('logistica.tratamiento')->insert([
        ['tratamiento_id' => 10, 'status' => 1],
        ['tratamiento_id' => 11, 'status' => 1],
        ['tratamiento_id' => 12, 'status' => 0],
    ]);
    DB::table('logistica.protocolo')->insert(['protocolo_id' => 20, 'nombre' => 'Protocolo A']);
    DB::table('logistica.medicina')->insert(['medicina_id' => 30, 'descripcion' => 'Medicamento A']);
    DB::table('logistica.medicina_trat')->insert([
        ['id' => 1, 'paciente_id' => 1, 'consulta_id' => 1, 'protocolo_id' => 20, 'medicina_id' => 30, 'tratamiento_id' => 10],
        ['id' => 2, 'paciente_id' => 1, 'consulta_id' => 1, 'protocolo_id' => 20, 'medicina_id' => 30, 'tratamiento_id' => 10],
        ['id' => 3, 'paciente_id' => 2, 'consulta_id' => 3, 'protocolo_id' => 20, 'medicina_id' => 30, 'tratamiento_id' => 11],
        ['id' => 4, 'paciente_id' => 3, 'consulta_id' => 5, 'protocolo_id' => 20, 'medicina_id' => 30, 'tratamiento_id' => 12],
    ]);

    $metrics = app(MedicoDashboardMetrics::class)->build();

    expect($metrics['historias_registradas'])->toBe(4)
        ->and($metrics['interconsultas_realizadas'])->toBe(2)
        ->and($metrics['pacientes_por_enfermedad'])->toBe([
            ['etiqueta' => 'Anemia', 'total' => 2],
            ['etiqueta' => 'Leucemia', 'total' => 1],
        ])
        ->and($metrics['pacientes_por_sexo'])->toBe([
            ['etiqueta' => 'Femenino', 'total' => 2],
            ['etiqueta' => 'Masculino', 'total' => 1],
            ['etiqueta' => 'No especificado', 'total' => 1],
        ])
        ->and($metrics['protocolos_activos'])->toBe(2)
        ->and($metrics['usos_por_protocolo_medicina'])->toBe([
            ['etiqueta' => 'Protocolo A', 'medicina' => 'Medicamento A', 'total' => 2],
        ])
        ->and($metrics['protocolos_disponibles'])->toBeTrue();

    DB::statement('DROP TABLE logistica.medicina_trat');
    DB::statement('DROP TABLE logistica.tratamiento');
    DB::statement('DROP TABLE logistica.protocolo');
    DB::statement('DROP TABLE logistica.medicina');

    $metricsWithoutProtocolTables = app(MedicoDashboardMetrics::class)->build();

    expect($metricsWithoutProtocolTables['protocolos_activos'])->toBeNull()
        ->and($metricsWithoutProtocolTables['usos_por_protocolo_medicina'])->toBe([])
        ->and($metricsWithoutProtocolTables['protocolos_disponibles'])->toBeFalse()
        ->and($metricsWithoutProtocolTables['historias_registradas'])->toBe(4);
});

it('renders live dashboard indicators and uses the safe JavaScript data encoder', function () {
    $dashboard = file_get_contents(resource_path('views/medico/dashboard.blade.php'));
    $layout = file_get_contents(resource_path('views/medico/index.blade.php'));
    $navbar = file_get_contents(resource_path('views/partials/navbar_med.blade.php'));
    $script = file_get_contents(public_path('assets/js/medico-dashboard.js'));

    expect($dashboard)
        ->toContain("\$dashboardMetrics['historias_registradas']")
        ->toContain("\$dashboardMetrics['protocolos_activos']")
        ->toContain("\$dashboardMetrics['interconsultas_realizadas']")
        ->toContain('\Illuminate\Support\Js::from($dashboardMetrics)')
        ->toContain('Consultas de segunda vez realizadas')
        ->not->toContain('disabled')
        ->not->toContain('Leucemia');

    expect($layout)
        ->toContain('assets/css/medico-dashboard.css')
        ->toContain('assets/js/medico-dashboard.js')
        ->not->toContain('dashboard-demo');

    expect($navbar)
        ->toContain("route('medico.index')")
        ->toContain('fas fa-home')
        ->toContain('Ir al panel principal del Médico');

    expect($script)
        ->toContain('metrics.pacientes_por_enfermedad')
        ->toContain('metrics.pacientes_por_sexo')
        ->toContain('metrics.usos_por_protocolo_medicina')
        ->toContain('metrics.protocolos_disponibles')
        ->toContain("femenino: '#e83e8c'")
        ->toContain("masculino: '#3c8dbc'")
        ->toContain('text: `${row.label} (${Math.round((row.total / sexTotal) * 100)}%)`')
        ->toContain('return rows;')
        ->not->toContain('No especificado')
        ->not->toContain('Chart.defaults.global.legend.labels.generateLabels')
        ->not->toContain('Medicamento alfa');
});
