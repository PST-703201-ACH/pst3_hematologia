<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Asistente administrativo')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">

    <!--Estilos del calendario-->
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/fullcalendar/main.css') }}">

    <!-- Custom App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('partials.navbar_admvo')

    @include('partials.sidebar_admvo')

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
                    @include('administrativo.dashboard')    
                    </div>
                </div>

                <div id="seccion-citas" class="vista_admvo d-none">
                    @include('administrativo.calendario')
                </div>
            </div>
        </section>
    </div>

</div>

<!-- jQuery -->
<script src="{{ asset('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Javascript de funcionamiento del calendario-->
<script src="{{ asset('assets/adminlte/plugins/fullcalendar/main.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/fullcalendar/locales/es.js') }}"></script>

<!-- AdminLTE -->
<script src="{{ asset('assets/adminlte/js/adminlte.min.js') }}"></script>

<!-- Custom App JS -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<script src="{{ asset('js/botones_admvo.js') }}"></script>
<script src="{{ asset('js/calendario.js') }}"></script>
<script src="{{ asset('js/registrar_cita.js') }}"></script>
<script src="{{ asset('js/modificar_cita.js') }}"></script>

@stack('scripts')
</body>
</html>