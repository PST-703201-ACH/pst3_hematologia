@extends('adminlte::page')

@section('title', 'Listado de Pacientes')

@section('content_header')
    <h1>Listado de Pacientes Registrados</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="icon fas fa-ban"></i> {{ $errors->first('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Pacientes en Sistema</h3>
            <div class="card-tools">
                <a href="{{ route('medico.pacientes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Paciente
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="tabla-pacientes" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>N° Historia</th>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Sexo</th>
                        <th>Status</th>
                        <th style="width: 250px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pacientes as $paciente)
                        <tr>
                            <td>{{ $paciente->hc }}</td>
                            <td>{{ $paciente->persona->cedula }}</td>
                            <td>{{ $paciente->persona->nombres }} {{ $paciente->persona->apellidos }}</td>
                            <td>{{ $paciente->persona->sexo }}</td>
                            <td>
                                <span class="badge {{ $paciente->status == 'Activo' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $paciente->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm" title="Ver Información">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" title="Historia Clínica">
                                        <i class="fas fa-notes-medical"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" title="Datos de Laboratorio">
                                        <i class="fas fa-microscope"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" title="Actualizar Datos">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="{{ route('medico.pacientes.destroy', $paciente->paciente_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este registro? Esta acción es irreversible (Temporal)');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Registro">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-pacientes').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                "responsive": true,
                "autoWidth": false,
            });
        });
    </script>
@stop
