<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gerente (Administrador del sistema)</title>
</head>
<body>
@extends('adminlte::page')

@section('title', 'Panel de Control')

@section('content')
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

        @include('administrador.ver')
@stop

@section('js')
<script src="{{ asset('js/botones_admin.js') }}"></script>
<script src="{{ asset('js/registrar_usuario.js') }}"></script>
<script src="{{ asset('js/listar_usuario.js') }}"></script>
<script src="{{ asset('js/dashboards_admin.js') }}"></script>
<script src="{{ asset('js/ver_usuario.js') }}"></script>
<script src="{{ asset('js/actualizar_usuario.js') }}"></script>
<script src="{{ asset('js/status_usuario.js') }}"></script>
@stop
</body>
</html>