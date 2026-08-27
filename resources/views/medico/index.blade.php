<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Médico')</title>

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
        
        <section class="content">
            <div class="container-fluid">
                @yield('content')

                <div class="card">
                    <div class="card-body">
                    @include('medico.dashboard')    
                    </div>
                </div>

                <div id="seccion-pacientes_listados" class="vista_med d-none">
                    @include('medico.pacientes.listado')
                </div>

                <div id="seccion-registrar_paciente" class="vista_med d-none">
                    @include('medico.pacientes.crear')
                </div>

                <div id="seccion-listar_consulta" class="vista_med d-none">
                    @include('medico.consultas.listado')
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
<script src="{{ asset('assets/js/botones_med.js') }}"></script>
<script src="{{ asset('assets/js/listar_paciente.js') }}"></script>
<script src="{{ asset('assets/js/registrar_paciente.js') }}"></script>

@stack('scripts')
</body>
</html>