@extends('adminlte::page')

@section('title', 'Detalle del Paciente')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalle del Paciente</h1>
        <a href="{{ route('medico.pacientes.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver al Listado
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @php
        $persona = $paciente->persona;
        $representantes = method_exists($paciente, 'representantes')
            ? $paciente->representantes
            : collect();
    @endphp

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <div class="d-inline-block rounded-circle bg-primary text-white text-center align-middle" style="width: 100px; height: 100px; line-height: 100px; font-size: 36px; font-weight: bold;">
                            {{ strtoupper(substr($persona->nombres, 0, 1) . substr($persona->apellidos, 0, 1)) }}
                        </div>
                    </div>
                    <h3 class="profile-username text-center font-weight-bold">{{ $persona->nombres }}</h3>
                    <p class="text-muted text-center">{{ $persona->apellidos }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item"><b>N° Historia Clínica</b> <span class="float-right badge badge-primary">{{ $paciente->hc }}</span></li>
                        <li class="list-group-item"><b>Cédula</b> <span class="float-right">{{ $persona->cedula }}</span></li>
                        <li class="list-group-item"><b>Sexo</b> <span class="float-right">{{ $persona->sexo }}</span></li>
                        <li class="list-group-item"><b>Edad</b> <span class="float-right">{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->age }} años</span></li>
                        <li class="list-group-item"><b>Estatus</b> <span class="float-right badge {{ $paciente->status == 'Activo' ? 'badge-success' : 'badge-danger' }}">{{ $paciente->status }}</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-info card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-id-card mr-2"></i> Información Personal y de Contacto</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><b>Fecha de Nacimiento:</b> {{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</p>
                            <p><b>Teléfono:</b> {{ $persona->telefono ?? 'No registrado' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><b>Correo:</b> {{ $persona->email ?? 'No registrado' }}</p>
                            <p><b>Dirección:</b> {{ $persona->direccion_exacta ?? 'No registrada' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-secondary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-map-marked-alt mr-2"></i> Ubicación Geográfica</h3></div>
                <div class="card-body">
                    Estado: <strong>{{ $persona->estado->nombre ?? 'N/A' }}</strong> |
                    Municipio: <strong>{{ $persona->municipio->nombre ?? 'N/A' }}</strong> |
                    Parroquia: <strong>{{ $persona->parroquia->nombre ?? 'N/A' }}</strong>
                </div>
            </div>

            @if($representantes->isNotEmpty())
                <div class="card card-success card-outline">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-users mr-2"></i> Representante del Paciente</h3></div>
                    <div class="card-body">
                        @foreach($representantes as $representante)
                            <p class="mb-1"><b>Nombre:</b> {{ $representante->persona->nombres }} {{ $representante->persona->apellidos }}</p>
                            <p class="mb-1"><b>Cédula:</b> {{ $representante->persona->cedula }}</p>
                            <p class="mb-0"><b>Parentesco:</b> {{ $representante->pivot->parentesco ?? 'No especificado' }}</p>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop