<?php

/* Checks that Nursing uses the shared clinical dashboard and its live metrics. */
use App\Http\Controllers\EnfermeriaController;
use App\Services\MedicoDashboardMetrics;

it('renders the nursing dashboard with live clinical metrics', function () {
    $dashboardMetrics = [
        'pacientes_por_enfermedad' => [],
        'pacientes_por_sexo' => [],
        'usos_por_protocolo_medicina' => [],
        'protocolos_disponibles' => true,
    ];
    $metrics = Mockery::mock(MedicoDashboardMetrics::class);
    $metrics->shouldReceive('build')->once()->andReturn($dashboardMetrics);

    $view = app(EnfermeriaController::class)->dashboard($metrics);
    $html = $view->render();

    expect($view->name())->toBe('enfermeria.index')
        ->and($view->getData()['dashboardMetrics'])->toBe($dashboardMetrics)
        ->and($html)->toContain('window.PST3ClinicalDashboardData')
        ->toContain('class="vista_enf" id="seccion-dashboard"')
        ->toContain('class="vista_enf d-none" id="seccion-jornada"')
        ->toContain('class="vista_enf d-none" id="seccion-sala"')
        ->toContain('Pacientes por enfermedad')
        ->toContain('Pacientes por sexo')
        ->toContain('Usos por protocolo y medicamento');
});

it('provides the home link and shared chart assets on the nursing panel', function () {
    $navbar = file_get_contents(resource_path('views/partials/navbar_enf.blade.php'));
    $panel = file_get_contents(resource_path('views/enfermeria/index.blade.php'));
    $route = app('router')->getRoutes()->getByName('enf.index');

    expect($navbar)
        ->toContain("route('enf.index')")
        ->toContain('fas fa-home')
        ->toContain('Ir al panel principal de Enfermería');

    expect($panel)
        ->toContain("partials.dashboard_clinico_graficas")
        ->toContain('assets/css/dashboard-clinico.css')
        ->toContain('assets/js/dashboard-clinico.js')
        ->toContain('assets/adminlte/plugins/chart.js/Chart.min.js')
        ->and($route->getActionName())->toBe(EnfermeriaController::class.'@dashboard');
});
