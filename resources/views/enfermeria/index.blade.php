<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Enfermería</title>
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar_enf')
    @include('partials.sidebar_enf')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Panel diario de Enfermería</h1>
                <p class="text-muted mb-0">Agenda clínica y registro de aplicaciones</p>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <div class="tab-content">
                    <div class="vista_enf" id="seccion-jornada">
                        @include('enfermeria.jornada')
                    </div>

                    <div class="vista_enf d-none" id="seccion-sala">
                        @include('enfermeria.observaciones')
                    </div>

                    <div class="vista_enf d-none" id="seccion-registrar-obs">
                        @include('enfermeria.registrar_obs')
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<script src="{{ asset('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/js/listar_jornada.js') }}"></script>
<script src="{{ asset('assets/js/registrar_obs.js') }}"></script>
<script src="{{ asset('assets/js/listar_observacion.js') }}"></script>
<script src="{{ asset('assets/js/botones_enf.js') }}"></script>
</body>
</html>
