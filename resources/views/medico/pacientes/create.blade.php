@extends('adminlte::page')

@section('title', 'Registrar Paciente')

@section('content_header')
    <h1>Registro de Nuevo Paciente</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Datos Personales</h3>
                </div>
                
                <form action="{{ route('medico.pacientes.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hc">N° Historia Clínica</label>
                                    <input type="text" name="hc" id="hc" class="form-control" placeholder="Ej: 0001" required value="{{ old('hc') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cedula">Cédula de Identidad</label>
                                    <input type="text" name="cedula" id="cedula" class="form-control" placeholder="Ej: V-12345678" required value="{{ old('cedula') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nombres">Nombres</label>
                                    <input type="text" name="nombres" id="nombres" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="apellidos">Apellidos</label>
                                    <input type="text" name="apellidos" id="apellidos" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sexo">Sexo</label>
                                    <select name="sexo" id="sexo" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="direccion_exacta">Dirección Exacta</label>
                                    <textarea name="direccion_exacta" id="direccion_exacta" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>Ubicación Geográfica</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado_id">Estado</label>
                                    <select name="estado_id" id="estado_id" class="form-control">
                                        <option value="">Seleccione...</option>
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado->estado_id }}">{{ $estado->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="municipio_id">Municipio</label>
                                    <select name="municipio_id" id="municipio_id" class="form-control">
                                        <option value="">Seleccione estado primero...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="parroquia_id">Parroquia</label>
                                    <select name="parroquia_id" id="parroquia_id" class="form-control">
                                        <option value="">Seleccione municipio primero...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary">Limpiar</button>
                        <button type="submit" class="btn btn-primary">Registrar Paciente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Manejo de Estados -> Municipios
            $('#estado_id').on('change', function() {
                var estadoId = $(this).val();
                $('#municipio_id').html('<option value="">Cargando...</option>');
                $('#parroquia_id').html('<option value="">Seleccione municipio primero...</option>');
                
                if (estadoId) {
                    $.get('{{ route("municipios.json") }}', { estado_id: estadoId }, function(data) {
                        var html = '<option value="">Seleccione Municipio...</option>';
                        data.forEach(function(item) {
                            html += '<option value="' + item.municipio_id + '">' + item.nombre + '</option>';
                        });
                        $('#municipio_id').html(html);
                    });
                } else {
                    $('#municipio_id').html('<option value="">Seleccione estado primero...</option>');
                }
            });

            // Manejo de Municipios -> Parroquias
            $('#municipio_id').on('change', function() {
                var municipioId = $(this).val();
                $('#parroquia_id').html('<option value="">Cargando...</option>');
                
                if (municipioId) {
                    $.get('{{ route("parroquias.json") }}', { municipio_id: municipioId }, function(data) {
                        var html = '<option value="">Seleccione Parroquia...</option>';
                        data.forEach(function(item) {
                            html += '<option value="' + item.parroquia_id + '">' + item.nombre + '</option>';
                        });
                        $('#parroquia_id').html(html);
                    });
                } else {
                    $('#parroquia_id').html('<option value="">Seleccione municipio primero...</option>');
                }
            });
        });
    </script>
@stop
