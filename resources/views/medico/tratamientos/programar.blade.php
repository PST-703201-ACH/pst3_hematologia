<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Asignar protocolo y programar tratamiento</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            El médico define la indicación, dosis, vía, fecha y sala. Enfermería solo registra la aplicación prescrita.
        </div>

        <form method="POST" action="{{ route('medico.tratamientos.store') }}" class="mb-4">
            @csrf
            <h4>Asignar protocolo a un paciente</h4>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="paciente_id">Paciente</label>
                    <select class="form-control" id="paciente_id" name="paciente_id" required>
                        <option value="">Seleccione</option>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->paciente_id }}" @selected(old('paciente_id') == $paciente->paciente_id)>
                                {{ $paciente->hc }} — {{ $paciente->persona?->nombres }} {{ $paciente->persona?->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    @error('paciente_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="consulta_id">Consulta que indica el tratamiento</label>
                    <select class="form-control" id="consulta_id" name="consulta_id" required>
                        <option value="">Seleccione</option>
                        @foreach($consultas as $consulta)
                            <option value="{{ $consulta->consulta_id }}" data-paciente="{{ $consulta->paciente_id }}" @selected(old('consulta_id') == $consulta->consulta_id)>
                                #{{ $consulta->consulta_id }} — HC {{ $consulta->hc }} — {{ $consulta->nombres }} {{ $consulta->apellidos }} — {{ $consulta->fecha_hora }}
                            </option>
                        @endforeach
                    </select>
                    @error('consulta_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="protocolo_id">Protocolo</label>
                    <select class="form-control" id="protocolo_id" name="protocolo_id" required>
                        <option value="">Seleccione</option>
                        @foreach($protocolos as $protocolo)
                            <option value="{{ $protocolo->protocolo_id }}" @selected(old('protocolo_id') == $protocolo->protocolo_id)>{{ $protocolo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('protocolo_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="fecha_inicio">Fecha de inicio del protocolo</label>
                    <input class="form-control" id="fecha_inicio" name="fecha_inicio" type="date" value="{{ old('fecha_inicio', today()->toDateString()) }}" required>
                </div>
                <div class="form-group col-12">
                    <label for="indicaciones">Indicaciones médicas</label>
                    <textarea class="form-control" id="indicaciones" name="indicaciones" rows="2">{{ old('indicaciones') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Asignar protocolo</button>
        </form>

        <hr>
        <form method="POST" action="{{ route('medico.tratamientos.sesiones.store') }}">
            @csrf
            <h4>Programar una sesión</h4>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="protocolo_tratamiento_id">Tratamiento asignado</label>
                    <select class="form-control" id="protocolo_tratamiento_id" name="protocolo_tratamiento_id" required>
                        <option value="">Seleccione</option>
                        @foreach($tratamientos as $tratamiento)
                            <option value="{{ $tratamiento->protocolo_tratamiento_id }}" data-protocolo="{{ $tratamiento->protocolo_id }}" @selected(old('protocolo_tratamiento_id') == $tratamiento->protocolo_tratamiento_id)>
                                HC {{ $tratamiento->paciente?->hc }} — {{ $tratamiento->paciente?->persona?->nombres }} {{ $tratamiento->paciente?->persona?->apellidos }} — {{ $tratamiento->protocolo?->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('protocolo_tratamiento_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="numero_ciclo">Número de ciclo</label>
                    <input class="form-control" id="numero_ciclo" name="numero_ciclo" type="number" min="1" value="{{ old('numero_ciclo', 1) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="medicina_pro_id">Medicamento del protocolo</label>
                    <select class="form-control" id="medicina_pro_id" name="medicina_pro_id" required>
                        <option value="">Seleccione</option>
                        @foreach($medicinasPro as $detalle)
                            <option value="{{ $detalle->medicina_pro_id }}" data-protocolo="{{ $detalle->protocolo_id }}" @selected(old('medicina_pro_id') == $detalle->medicina_pro_id)>
                                {{ $detalle->protocolo }} — Fase {{ $detalle->fase }} — {{ $detalle->medicamento }}
                            </option>
                        @endforeach
                    </select>
                    @error('medicina_pro_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="sala_nombre">Sala</label>
                    <input class="form-control" id="sala_nombre" name="sala_nombre" list="salas-disponibles" value="{{ old('sala_nombre') }}" maxlength="80" required>
                    <datalist id="salas-disponibles">
                        @foreach($salas as $sala)<option value="{{ $sala->nombre }}">@endforeach
                    </datalist>
                    @error('sala_nombre') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="fecha_hora">Fecha y hora programada</label>
                    <input class="form-control" id="fecha_hora" name="fecha_hora" type="datetime-local" value="{{ old('fecha_hora') }}" required>
                    @error('fecha_hora') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="tipo_sesion">Tipo de sesión</label>
                    <input class="form-control" id="tipo_sesion" name="tipo_sesion" maxlength="50" value="{{ old('tipo_sesion', 'Administración') }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="dosis">Dosis prescrita</label>
                    <input class="form-control" id="dosis" name="dosis" maxlength="100" value="{{ old('dosis') }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="via_administracion">Vía de administración</label>
                    <input class="form-control" id="via_administracion" name="via_administracion" maxlength="100" value="{{ old('via_administracion') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-calendar-check mr-1"></i> Programar sesión</button>
        </form>
    </div>
</div>
