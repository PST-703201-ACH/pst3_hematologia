<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-plus mr-1"></i> Datos Personales y de Identificación</h3>
            </div>

            <form action="{{ route('medico.pacientes.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if($errors->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="icon fas fa-ban"></i> {{ $errors->first('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hc">N° Historia Clínica <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-folder-open"></i></span>
                                    </div>
                                    <input type="text" name="hc" id="hc" class="form-control @error('hc') is-invalid @enderror" placeholder="Ej: HC-0482" required value="{{ old('hc') }}">
                                </div>
                                @error('hc')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label for="cedula">Cédula de Identidad <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-3">
                                    <select name="nacionalidad" id="nacionalidad" class="form-control @error('nacionalidad') is-invalid @enderror" required>
                                        <option value="V" {{ old('nacionalidad') == 'V' ? 'selected' : '' }}>V (Venezolano)</option>
                                        <option value="E" {{ old('nacionalidad') == 'E' ? 'selected' : '' }}>E (Extranjero)</option>
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="cedula" id="cedula" class="form-control @error('cedula') is-invalid @enderror" placeholder="Ej: 25123456" required value="{{ old('cedula') }}">
                                    @error('cedula')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @error('cedula_completa')
                                        <span class="text-danger d-block mt-1" style="font-size: 80%;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre1">Primer Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre1" id="nombre1" class="form-control @error('nombre1') is-invalid @enderror" required value="{{ old('nombre1') }}" placeholder="Ej: Juan">
                                @error('nombre1')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre2">Segundo Nombre</label>
                                <input type="text" name="nombre2" id="nombre2" class="form-control @error('nombre2') is-invalid @enderror" value="{{ old('nombre2') }}" placeholder="Ej: Carlos">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="apellido1">Primer Apellido <span class="text-danger">*</span></label>
                                <input type="text" name="apellido1" id="apellido1" class="form-control @error('apellido1') is-invalid @enderror" required value="{{ old('apellido1') }}" placeholder="Ej: Pérez">
                                @error('apellido1')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="apellido2">Segundo Apellido</label>
                                <input type="text" name="apellido2" id="apellido2" class="form-control @error('apellido2') is-invalid @enderror" value="{{ old('apellido2') }}" placeholder="Ej: Rodríguez">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" required value="{{ old('fecha_nacimiento') }}">
                                @error('fecha_nacimiento')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sexo">Sexo <span class="text-danger">*</span></label>
                                <select name="sexo" id="sexo" class="form-control @error('sexo') is-invalid @enderror" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Masculino" {{ old('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ old('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                </select>
                                @error('sexo')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="paciente@correo.com">
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefono">Teléfono <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">0</span>
                                    </div>
                                    <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" placeholder="Ej: 4121234567" required value="{{ old('telefono') }}">
                                </div>
                                @error('telefono')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="direccion_exacta">Dirección Exacta</label>
                                <textarea name="direccion_exacta" id="direccion_exacta" class="form-control @error('direccion_exacta') is-invalid @enderror" rows="2" placeholder="Calle, edificio, casa, piso...">{{ old('direccion_exacta') }}</textarea>
                                @error('direccion_exacta')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4 text-primary border-bottom pb-2"><i class="fas fa-map-marked-alt mr-1"></i> Ubicación Geográfica</h5>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="estado_id">Estado <span class="text-danger">*</span></label>
                                <select name="estado_id" id="estado_id" class="form-control @error('estado_id') is-invalid @enderror" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->estado_id }}" {{ old('estado_id') == $estado->estado_id ? 'selected' : '' }}>{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('estado_id')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="municipio_id">Municipio</label>
                                <select name="municipio_id" id="municipio_id" class="form-control @error('municipio_id') is-invalid @enderror">
                                    <option value="">Seleccione estado primero...</option>
                                </select>
                                @error('municipio_id')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="parroquia_id">Parroquia</label>
                                <select name="parroquia_id" id="parroquia_id" class="form-control @error('parroquia_id') is-invalid @enderror">
                                    <option value="">Seleccione municipio primero...</option>
                                </select>
                                @error('parroquia_id')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4 text-primary border-bottom pb-2"><i class="fas fa-users-cog mr-1"></i> Asociación de Representante (Opcional)</h5>
                    <div class="row mt-3">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="representante_id">Buscar Representante</label>
                                <select name="representante_id" id="representante_id" class="form-control select2 @error('representante_id') is-invalid @enderror" style="width: 100%;">
                                    <option value="">Ninguno (El paciente no tiene o no requiere representante)</option>
                                    @foreach($representantes as $rep)
                                        <option value="{{ $rep->representante_id }}" {{ old('representante_id') == $rep->representante_id ? 'selected' : '' }}>
                                            {{ $rep->persona->cedula }} - {{ $rep->persona->nombres }} {{ $rep->persona->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="parentesco">Parentesco</label>
                                <select name="parentesco" id="parentesco" class="form-control @error('parentesco') is-invalid @enderror">
                                    <option value="">Seleccione parentesco...</option>
                                    <option value="Madre" {{ old('parentesco') == 'Madre' ? 'selected' : '' }}>Madre</option>
                                    <option value="Padre" {{ old('parentesco') == 'Padre' ? 'selected' : '' }}>Padre</option>
                                    <option value="Abuelo/a" {{ old('parentesco') == 'Abuelo/a' ? 'selected' : '' }}>Abuelo/a</option>
                                    <option value="Tío/a" {{ old('parentesco') == 'Tío/a' ? 'selected' : '' }}>Tío/a</option>
                                    <option value="Tutor Legal" {{ old('parentesco') == 'Tutor Legal' ? 'selected' : '' }}>Tutor Legal</option>
                                    <option value="Otro" {{ old('parentesco') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('parentesco')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right bg-light border-top">
                    <button type="button" id="btnCancelarReg" class="btn btn-danger mr-2">
                        <i class="fas fa-times mr-1"></i> Salir
                    </button>
                    <button type="reset" class="btn btn-default mr-2">
                        <i class="fas fa-eraser mr-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Registrar Paciente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#representante_id').select2({
            placeholder: 'Buscar representante por Cédula o Nombre...',
            allowClear: true,
            ajax: {
                url: '{{ route("medico.representantes.buscar") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            minimumInputLength: 1
        });

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
