<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gerente (Administrador)')</title>

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

    @include('partials.navbar_admin')

    @include('partials.sidebar_admin')

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
                    @include('administrador.dashboard')    
                    </div>
                </div>

                <div id="seccion-usuarios" class="vista_admin d-none">
                    @include('administrador.usuarios')
                </div>

                <div id="registro-usuario" class="vista_admin d-none">
                    @include('administrador.registrar')
                </div>
                <div id="actualizar-usuario" class="vista_admin d-none">
                    @include('administrador.actualizar')
                </div>

                <div id="seccion-catalogo" class="vista_admin d-none">
                    @include('administrador.catalogo')
                </div>

                <div id="registrar-enfermedad" class="vista_admin d-none">
                    @include('administrador.registrar_enf')
                </div>

                <div id="actualizar-enfermedad" class="vista_admin d-none">
                    @include('administrador.actualizar_enf')
                </div>

                <div id="registrar-medicina" class="vista_admin d-none">
                    @include('administrador.registrar_med')
                </div>

                <div id="actualizar-medicina" class="vista_admin d-none">
                    @include('administrador.actualizar_med')
                </div>

                <div id="seccion-auditoria" class="vista_admin d-none">
                    @include('administrador.auditoria')
                </div>


                    @include('administrador.ver')
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

<script src="{{ asset('assets/js/botones_admin.js') }}"></script>
<script src="{{ asset('assets/js/registrar_usuario.js') }}"></script>
<script src="{{ asset('assets/js/listar_usuario.js') }}"></script>
<script src="{{ asset('assets/js/dashboards_admin.js') }}"></script>
<script src="{{ asset('assets/js/ver_usuario.js') }}"></script>
<script src="{{ asset('assets/js/actualizar_usuario.js') }}"></script>
<script src="{{ asset('assets/js/status_usuario.js') }}"></script>
<script src="{{ asset('assets/js/catalogo_med.js') }}"></script>
<script src="{{ asset('assets/js/registrar_enf.js') }}"></script>
<script src="{{ asset('assets/js/actualizar_enf.js') }}"></script>
<script src="{{ asset('assets/js/listar_auditoria.js') }}"></script>

@stack('scripts')
</body>
</html>