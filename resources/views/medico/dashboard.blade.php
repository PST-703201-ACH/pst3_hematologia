@extends('adminlte::page')

@section('title', 'Dashboard Médico')

@section('content_header')
<h1>Panel de Control - Médico</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bienvenido, Dr. {{ Auth::user()->persona->nombres }} {{ Auth::user()->persona->apellidos }}</h3>
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
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@stop

@section('js')
<script src="{{ asset('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/js/adminlte.min.js') }}"></script>
<script>
    console.log('Dashboard Médico Cargado');
</script>
@stop