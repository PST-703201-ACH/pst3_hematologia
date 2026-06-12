<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Médico')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">

    <!-- Custom App CSS -->
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
                @yield('content_header')
            </div>
        </section>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{--Bienvenido, Dr. {{ Auth::user()->persona->nombres }} {{ Auth::user()->persona->apellidos }}--}}</h3>
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
                </div>
            </div>
        <section class="content">
            <div class="container-fluid">
                @yield('content')

                <div id="seccion-pacientes_listados" class="vista_med d-none">
                    @include('medico.pacientes.index')
                </div>

                <div id="seccion-registrar_paciente" class="vista_med d-none">
                    @include('medico.pacientes.crear')
                </div>
            </div>
        </section>
    </div>

</div>

<!-- jQuery -->
<script src="{{ asset('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE -->
<script src="{{ asset('assets/adminlte/js/adminlte.min.js') }}"></script>

<!-- Custom App JS -->
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('js/botones_med.js') }}"></script>
<script src="{{ asset('js/listar_paciente.js') }}"></script>
<script src="{{ asset('js/registrar_paciente.js') }}"></script>

@stack('scripts')
</body>
</html>