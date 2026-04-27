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

@section('content_header')
    <h1>Gerente</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <p>Aca estara el dashboard</p>
        </div>
    </div>

    <div id="seccion-usuarios" class="vista_admin d-none">
    	@include('administrador.usuarios')
    </div>

    <div id="registro-usuario" class="vista_admin d-none">
        @include('administrador.registrar')
    </div>
@stop

@section('js')
<script src="{{ asset('js/botones_sidebar.js') }}"></script>
<script src="{{ asset('js/registrar_usuario.js') }}"></script>
@stop
</body>
</html>