<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bienvenido al panel de Médico</h3>
    </div>
    <div class="card-body">
        <p>Resumen de historias clínicas, protocolos activos e interconsultas realizadas.</p>

        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($dashboardMetrics['historias_registradas']) }}</h3>
                        <p>Historias Clínicas</p>
                    </div>
                    <div class="icon"><i class="fas fa-notes-medical" aria-hidden="true"></i></div>
                    <span class="small-box-footer">Historias registradas</span>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $dashboardMetrics['protocolos_activos'] === null ? 'N/D' : number_format($dashboardMetrics['protocolos_activos']) }}</h3>
                        <p>Protocolos Activos</p>
                    </div>
                    <div class="icon"><i class="fas fa-prescription-bottle-alt" aria-hidden="true"></i></div>
                    <span class="small-box-footer">
                        {{ $dashboardMetrics['protocolos_disponibles'] ? 'Asignaciones vigentes a pacientes' : 'Fuente de protocolos no disponible' }}
                    </span>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($dashboardMetrics['interconsultas_realizadas']) }}</h3>
                        <p>Interconsultas atendidas</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-md" aria-hidden="true"></i></div>
                    <span class="small-box-footer">Consultas de segunda vez realizadas</span>
                </div>
            </div>
        </div>

        <section class="medico-dashboard" aria-labelledby="medico-dashboard-title">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                <h4 id="medico-dashboard-title" class="h5 mb-1">Resumen clínico</h4>
                <small class="text-muted">Datos calculados desde los registros del sistema</small>
            </div>

            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <article class="card card-outline card-primary h-100">
                        <div class="card-header"><h5 class="card-title">Pacientes por enfermedad</h5></div>
                        <div class="card-body">
                            <div class="medico-dashboard-chart">
                                <canvas id="graficoPacientesEnfermedad" role="img" aria-label="Pacientes atendidos por enfermedad"></canvas>
                                <p id="sinDatosEnfermedad" class="medico-dashboard-empty d-none">No hay consultas con enfermedad registrada.</p>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-4 col-md-6">
                    <article class="card card-outline card-primary h-100">
                        <div class="card-header"><h5 class="card-title">Pacientes por sexo</h5></div>
                        <div class="card-body">
                            <div class="medico-dashboard-chart">
                                <canvas id="graficoPacientesSexo" role="img" aria-label="Pacientes registrados por sexo"></canvas>
                                <p id="sinDatosSexo" class="medico-dashboard-empty d-none">No hay pacientes con sexo registrado.</p>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-4 col-md-12">
                    <article class="card card-outline card-primary h-100">
                        <div class="card-header"><h5 class="card-title">Usos por protocolo y medicamento</h5></div>
                        <div class="card-body">
                            <div class="medico-dashboard-chart">
                                <canvas id="graficoTratamientosAsignados" role="img" aria-label="Usos de protocolos asignados por medicamento"></canvas>
                                <p id="sinDatosProtocolos" class="medico-dashboard-empty d-none">No hay usos registrados de protocolos activos.</p>
                                <div id="protocolosNoDisponibles" class="alert alert-warning d-none mb-0" role="status">
                                    No se puede calcular este gráfico: falta alguna tabla de asignación de protocolos en la base de datos.
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    window.PST3MedicoDashboardData = {{ \Illuminate\Support\Js::from($dashboardMetrics) }};
</script>
