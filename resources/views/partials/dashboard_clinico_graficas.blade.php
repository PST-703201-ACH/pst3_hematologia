{{-- Gráficas clínicas compartidas por los paneles de Médico y Enfermería. --}}
<section class="clinical-dashboard" aria-labelledby="clinical-dashboard-title">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <h4 id="clinical-dashboard-title" class="h5 mb-1">Resumen clínico</h4>
        <small class="text-muted">Datos calculados desde los registros del sistema</small>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6">
            <article class="card card-outline card-primary h-100">
                <div class="card-header"><h5 class="card-title">Pacientes por enfermedad</h5></div>
                <div class="card-body">
                    <div class="clinical-dashboard-chart">
                        <canvas id="graficoPacientesEnfermedad" role="img" aria-label="Pacientes atendidos por enfermedad"></canvas>
                        <p id="sinDatosEnfermedad" class="clinical-dashboard-empty d-none">No hay consultas con enfermedad registrada.</p>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-4 col-md-6">
            <article class="card card-outline card-primary h-100">
                <div class="card-header"><h5 class="card-title">Pacientes por sexo</h5></div>
                <div class="card-body">
                    <div class="clinical-dashboard-chart">
                        <canvas id="graficoPacientesSexo" role="img" aria-label="Pacientes registrados por sexo"></canvas>
                        <p id="sinDatosSexo" class="clinical-dashboard-empty d-none">No hay pacientes con sexo registrado.</p>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-4 col-md-12">
            <article class="card card-outline card-primary h-100">
                <div class="card-header"><h5 class="card-title">Usos por protocolo y medicamento</h5></div>
                <div class="card-body">
                    <div class="clinical-dashboard-chart">
                        <canvas id="graficoTratamientosAsignados" role="img" aria-label="Usos de protocolos asignados por medicamento"></canvas>
                        <p id="sinDatosProtocolos" class="clinical-dashboard-empty d-none">No hay usos registrados de protocolos activos.</p>
                        <div id="protocolosNoDisponibles" class="alert alert-warning d-none mb-0" role="status">
                            No se puede calcular este gráfico: falta alguna tabla de asignación de protocolos en la base de datos.
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

<script>
    window.PST3ClinicalDashboardData = {{ \Illuminate\Support\Js::from($dashboardMetrics) }};
</script>
