<?php

/** Comprueba la integración visual temporal del dashboard Médico sin depender de datos backend. */
it('shows temporary demo charts first while keeping the patient list available from its navigation', function () {
    $dashboard = file_get_contents(resource_path('views/medico/dashboard.blade.php'));
    $layout = file_get_contents(resource_path('views/medico/index.blade.php'));
    $sidebar = file_get_contents(resource_path('views/partials/sidebar_med.blade.php'));
    $script = file_get_contents(public_path('assets/js/medico-dashboard-demo.js'));

    expect($dashboard)
        ->toContain('Historias Clínicas')
        ->toContain('Protocolos Activos')
        ->toContain('Interconsultas')
        ->toContain('Pacientes por enfermedad')
        ->toContain('Pacientes por sexo')
        ->toContain('Tratamientos por protocolo y medicamento')
        ->toContain('disabled')
        ->toContain('TEMPORAL')
        ->not->toContain('Auth::user()');

    expect(substr_count($dashboard, 'disabled aria-disabled="true"'))->toBe(3);

    expect($layout)
        ->toContain('id="seccion-pacientes_listados" class="vista_med d-none"')
        ->toContain('assets/adminlte/plugins/chart.js/Chart.min.js')
        ->toContain('assets/css/medico-dashboard-demo.css')
        ->toContain('assets/js/medico-dashboard-demo.js');

    expect($sidebar)->toContain('id="btnListarPa"');

    expect($script)
        ->toContain("type: 'pie'")
        ->toContain("'#3c8dbc', '#e83e8c'")
        ->toContain('generateLabels')
        ->toContain('%')
        ->toContain('Leucemia')
        ->toContain('Medicamento alfa');
});
