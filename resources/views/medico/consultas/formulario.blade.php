<div class="container-fluid">
    <div class="card card-primary card-outline shadow-sm mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm mr-2 js-volver-consultas" title="Volver al listado" aria-label="Volver al listado">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h3 class="card-title mb-0">Consulta Médica</h3>
                </div>
                <p class="mb-0 text-muted">
                    <strong>Paciente:</strong> {{ trim(($persona->nombres ?? $cita->nombres_paciente ?? '') . ' ' . ($persona->apellidos ?? $cita->apellidos_paciente ?? '')) }}
                </p>
                <p class="mb-0 text-muted">
                    <strong>Representante:</strong> {{ trim(($cita->nombres_representante ?? '') . ' ' . ($cita->apellidos_representante ?? '')) }}
                </p>
            </div>
            <div class="text-md-right">
                <div class="text-muted small">Historia Clínica</div>
                <div class="font-weight-bold h5 mb-0">{{ $cita->numero_hc ?? ($paciente->hc ?? 'Sin HC') }}</div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('medico.consultas.store') }}" method="POST">
        @csrf
        <input type="hidden" name="cita_id" value="{{ $cita->cita_id }}">
        <input type="hidden" name="es_primera_consulta" value="{{ $esPrimeraConsulta ? 1 : 0 }}">

        <div class="row">
            <div class="col-lg-6">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Clasificación</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tipo_consulta">Tipo de consulta</label>
                            <input type="hidden" name="tipo_id" value="{{ $tipoConsultaId }}">
                            <input type="text" id="tipo_consulta" class="form-control" value="{{ $tipoConsultaNombre }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="enfermedad_id">Enfermedad</label>
                            @if ($esPrimeraConsulta)
                                <select name="enfermedad_id" id="enfermedad_id" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach ($enfermedades as $enfermedad)
                                        <option value="{{ $enfermedad->enfermedad_id }}" {{ old('enfermedad_id') == $enfermedad->enfermedad_id ? 'selected' : '' }}>{{ $enfermedad->descripcion }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="enfermedad_id" value="{{ $enfermedadSeleccionada?->enfermedad_id }}">
                                <input type="text" id="enfermedad_id" class="form-control" value="{{ $enfermedadSeleccionada?->descripcion ?? 'Sin enfermedad asignada' }}" readonly>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Signos vitales</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="peso">Peso (kg)</label>
                                <input type="number" step="0.1" min="0" name="peso" id="peso" class="form-control" value="{{ old('peso') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="talla">Talla (cm)</label>
                                <input type="number" step="0.1" min="0" name="talla" id="talla" class="form-control" value="{{ old('talla') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="sc">SC</label>
                                <input type="number" step="0.01" min="0" name="sc" id="sc" class="form-control" value="{{ old('sc') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="fc">FC</label>
                                <input type="number" step="0.1" min="0" name="fc" id="fc" class="form-control" value="{{ old('fc') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="fr">FR</label>
                                <input type="number" step="0.1" min="0" name="fr" id="fr" class="form-control" value="{{ old('fr') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($esPrimeraConsulta)
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Antecedentes y síntomas iniciales</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="antecedentes_personales">Antecedentes Personales</label>
                        <textarea name="antecedentes_personales" id="antecedentes_personales" rows="3" class="form-control">{{ old('antecedentes_personales') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="antecedentes_familiares">Antecedentes Familiares</label>
                        <textarea name="antecedentes_familiares" id="antecedentes_familiares" rows="3" class="form-control">{{ old('antecedentes_familiares') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="signos_sintomas_iniciales">Signos y Síntomas Iniciales</label>
                        <textarea name="signos_sintomas_iniciales" id="signos_sintomas_iniciales" rows="3" class="form-control">{{ old('signos_sintomas_iniciales') }}</textarea>
                    </div>
                </div>
            </div>
        @endif

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Evolución y plan</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="subjetivo">Evolución Subjetiva</label>
                    <textarea name="subjetivo" id="subjetivo" rows="4" class="form-control">{{ old('subjetivo') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="plan_trabajo">Plan de Trabajo</label>
                    <textarea name="plan_trabajo" id="plan_trabajo" rows="3" class="form-control">{{ old('plan_trabajo') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="proxima_cita">Próxima Cita</label>
                    <input type="date" name="proxima_cita" id="proxima_cita" class="form-control" value="{{ old('proxima_cita') }}">
                </div>
            </div>
        </div>

        <div class="text-right mb-4">
            <button type="button" class="btn btn-secondary mr-2 js-volver-consultas">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Consulta</button>
        </div>
    </form>
</div>
