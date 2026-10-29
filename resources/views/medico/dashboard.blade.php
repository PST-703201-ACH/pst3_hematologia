<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bienvenido al panel de Médico</h3>
    </div>
    <div class="card-body">
        <p>Desde aquí podrá gestionar historias clínicas, interconsultas y protocolos de tratamiento</p>

        <div class="row">
            <!-- Estadísticas Rápidas -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>0</h3>
                        <p>Historias Clínicas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>0</h3>
                        <p>Protocolos Activos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-prescription-bottle-alt"></i>
                    </div>
                    <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>0</h3>
                        <p>Interconsultas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- TEMPORAL: gráficos con datos ficticios. Si no se aprueba, eliminar esta sección y las referencias Chart.js/CSS/JS del index del panel Médico. -->
        <section class="medico-dashboard-demo" aria-labelledby="medico-dashboard-demo-title">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                <div>
                    <h4 id="medico-dashboard-demo-title" class="h5 mb-1">Resumen clínico</h4>
                </div>
                <span class="badge badge-warning">Demostración</span>
            </div>

            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <article class="card card-outline card-primary h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Pacientes por enfermedad</h5>
                        </div>
                        <div class="card-body">
                            <div class="medico-dashboard-chart">
                                <canvas id="graficoPacientesEnfermedad" role="img" aria-label="Gráfico demostrativo de pacientes por enfermedad"></canvas>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-4 col-md-6">
                    <article class="card card-outline card-primary h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Pacientes por sexo</h5>
                        </div>
                        <div class="card-body">
                            <div class="medico-dashboard-chart">
                                <canvas id="graficoPacientesSexo" role="img" aria-label="Gráfico circular demostrativo de pacientes por sexo"></canvas>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-4 col-md-12">
                    <article class="card card-outline card-primary h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Tratamientos por protocolo y medicamento</h5>
                        </div>
                        <div class="card-body">
                            <div class="medico-dashboard-chart">
                                <canvas id="graficoTratamientosAsignados" role="img" aria-label="Gráfico demostrativo de tratamientos asignados por protocolo y medicamento"></canvas>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</div>
