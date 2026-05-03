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
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log('Dashboard Médico Cargado'); </script>
@stop
