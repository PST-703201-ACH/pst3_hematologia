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

        @include('partials.dashboard_clinico_graficas')
    </div>
</div>
