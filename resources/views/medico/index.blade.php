<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Médico')</title>

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

                <div id="seccion-dashboard" class="vista_med">
                    @include('medico.dashboard')
                </div>

                <div id="seccion-pacientes_listados" class="vista_med">
                    @include('medico.pacientes.listado')
                </div>

                <div id="seccion-paciente_detalle" class="vista_med d-none">
                    <div id="contenedor-paciente-detalle"></div>
                </div>

                <div id="seccion-consultas" class="vista_med d-none">
                    @include('medico.consultas.listado')
                </div>

                <div id="seccion-consulta-registrar" class="vista_med d-none">
                    @include('medico.consultas.registrar')
                </div>

                @include('medico.consultas.ver')
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
<script src="{{ asset('assets/js/listar_consulta.js') }}"></script>
<script src="{{ asset('assets/js/registrar_con.js') }}"></script>
<script src="{{ asset('assets/js/ver_consulta.js') }}"></script>

<!--REVISANDO-->
<script src="{{ asset('assets/js/programar_tratamiento.js') }}"></script>

@stack('scripts')
</body>
</html>
