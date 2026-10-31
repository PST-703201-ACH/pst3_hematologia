<?php

it('renders the frontend-only clinical history interface with its demo notice and search controls', function () {
    $html = view('administrador.historia_clinica')->render();
    $usersHtml = view('administrador.usuarios')->render();
    $adminLayout = file_get_contents(resource_path('views/administrador/index.blade.php'));
    $doctorLayout = file_get_contents(resource_path('views/medico/index.blade.php'));
    $doctorSidebar = file_get_contents(resource_path('views/partials/sidebar_med.blade.php'));
    $dataScript = file_get_contents(public_path('assets/js/historia-clinica-data.js'));
    $templatesScript = file_get_contents(public_path('assets/js/historia-clinica-templates.js'));
    $controllerScript = file_get_contents(public_path('assets/js/historia-clinica-controller.js'));

    expect($html)
        ->toContain('Historia Clínica')
        ->toContain('Vista de demostración')
        ->toContain('buscarHistoriaPaciente')
        ->toContain('resultadosHistoriaClinica')
        ->toContain('detalleHistoriaClinica')
        ->toContain('No se consultan ni modifican datos reales');

    expect($usersHtml)->toContain('id="btnHistoriaClinica"');

    expect($adminLayout)
        ->toContain("@include('administrador.historia_clinica')")
        ->toContain('assets/css/historia-clinica.css')
        ->toContain('assets/js/historia-clinica-data.js')
        ->toContain('assets/js/historia-clinica-templates.js')
        ->toContain('assets/js/historia-clinica-controller.js');

    expect($doctorSidebar)
        ->toContain('id="btnHistoriaClinicaMed"')
        ->toContain('<p>Historia Clínica</p>');

    expect($doctorLayout)
        ->toContain("@include('administrador.historia_clinica')")
        ->toContain('assets/css/historia-clinica.css')
        ->toContain('assets/js/historia-clinica-data.js')
        ->toContain('assets/js/historia-clinica-templates.js')
        ->toContain('assets/js/historia-clinica-controller.js');

    expect($dataScript)
        ->toContain('DEMO-HC-001')
        ->toContain('demo-paciente-001')
        ->toContain('window.PST3ClinicalHistoryData');

    expect($templatesScript)
        ->toContain('Código CIE-10')
        ->toContain('Controles y tratamiento')
        ->toContain('PST3ClinicalHistoryTemplates');

    expect($controllerScript)
        ->toContain('buscarHistoriaPaciente')
        ->toContain('PST3ClinicalHistoryData');
});
