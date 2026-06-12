<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Administrativo</title>
</head>
<body>
@extends('adminlte::page')

@section('title', 'Panel de Control')

@section('content')
    <div class="card">
        <div class="card-body">
        @include('administrativo.dashboard')    
        </div>
    </div>

    <div id="seccion-citas" class="vista_admvo d-none">
        @include('administrativo.citas')
    </div>
@stop

@section('js')
<script src="{{ asset('js/botones_admvo.js') }}"></script>
@stop
</body>
</html>