<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Médico</title>

    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar_med')
    @include('partials.sidebar_med')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Panel de Control - Médico</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @php
                    $pacientes = $pacientes ?? collect();
                    $estados = $estados ?? \App\Models\Estado::all();
                    $representantes = $representantes ?? collect();
                @endphp

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bienvenido, Dr. {{ Auth::user()->persona->nombres ?? '' }} {{ Auth::user()->persona->apellidos ?? '' }}</h3>
                    </div>
                    <div class="card-body">
                        <p>Desde aquí podrá gestionar historias clínicas, interconsultas y protocolos de tratamiento.</p>

                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>0</h3>
                                        <p>Historias Clínicas</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-notes-medical"></i></div>
                                    <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>0</h3>
                                        <p>Protocolos Activos</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-prescription-bottle-alt"></i></div>
                                    <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>0</h3>
                                        <p>Interconsultas</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-user-md"></i></div>
                                    <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="seccion-pacientes_listados" class="vista_med">
                    @include('medico.pacientes.index')
                </div>

                <div id="seccion-registrar_paciente" class="vista_med d-none">
                    @include('medico.pacientes.create')
                </div>
            </div>
        </section>
    </div>
</div>

<script src="{{ asset('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/adminlte/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/botones_med.js') }}"></script>

@stack('scripts')
</body>
</html>
