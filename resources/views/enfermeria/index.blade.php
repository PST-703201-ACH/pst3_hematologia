<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Enfermería</title>
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar_med')
    @include('partials.sidebar_enfermeria')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Panel diario de Enfermería</h1>
                <p class="text-muted mb-0">Agenda clínica y registro de aplicaciones</p>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card card-primary card-outline">
                    <div class="card-header"><h3 class="card-title">Tratamientos programados</h3></div>
                    <div class="card-body">
                        <form id="filtros-enfermeria" method="GET" action="{{ route('enfermeria.index') }}"
                              data-url="{{ route('enfermeria.sesiones.index') }}"
                              data-apply-template="{{ route('enfermeria.sesiones.aplicar', ['sesionId' => '__ID__']) }}">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-3">
                                    <label for="filtro-fecha">Jornada</label>
                                    <input class="form-control" type="date" id="filtro-fecha" name="fecha" value="{{ request('fecha', today()->toDateString()) }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filtro-sala">Sala</label>
                                    <select class="form-control" id="filtro-sala" name="sala_id">
                                        <option value="">Todas las salas</option>
                                        @foreach($salas as $sala)
                                            <option value="{{ $sala->sala_id }}" @selected(request('sala_id') == $sala->sala_id)>{{ $sala->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filtro-paciente">Paciente / H.C.</label>
                                    <input class="form-control" id="filtro-paciente" name="paciente" value="{{ request('paciente') }}" maxlength="100">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="filtro-estado">Estado</label>
                                    <select class="form-control" id="filtro-estado" name="status">
                                        <option value="">Todos</option>
                                        @foreach(['Programada', 'Realizada', 'Suspendida', 'Cancelada'] as $estado)
                                            <option value="{{ $estado }}" @selected(request('status') === $estado)>{{ $estado }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-1">
                                    <button class="btn btn-primary btn-block" type="submit" title="Actualizar agenda">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div id="error-agenda-enfermeria" class="alert alert-danger d-none" role="alert"></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Hora</th><th>Paciente / H.C.</th><th>Protocolo / medicamento</th>
                                        <th>Dosis y vía</th><th>Sala</th><th>Estado / acción</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpo-sesiones-enfermeria">
                                    @forelse($sesionesProgramadas as $sesion)
                                        <tr>
                                            <td>{{ $sesion->fecha_hora }}</td>
                                            <td>{{ $sesion->nombres }} {{ $sesion->apellidos }}<br><small>H.C. {{ $sesion->hc }}</small></td>
                                            <td>{{ $sesion->protocolo }}<br><small>{{ $sesion->medicamento }} — Ciclo {{ $sesion->numero_ciclo }}</small></td>
                                            <td>{{ $sesion->dosis }}<br><small>{{ $sesion->via_administracion }}</small></td>
                                            <td>{{ $sesion->sala }}</td>
                                            <td>{{ $sesion->status }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center">No hay sesiones para los filtros seleccionados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">La agenda se actualiza automáticamente cada 30 segundos.</small>
                    </div>
                </div>

                <div class="card card-danger card-outline">
                    <div class="card-header"><h3 class="card-title">Transfusiones programadas — trazabilidad de lotes</h3></div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>Paciente / H.C.</th><th>Componente</th><th>Lote</th><th>Programada</th><th>Volumen</th><th>Registrar aplicación</th></tr></thead>
                            <tbody>
                                @forelse($transfusionesProgramadas as $transfusion)
                                    <tr>
                                        <td>{{ $transfusion->nombres }} {{ $transfusion->apellidos }}<br><small>H.C. {{ $transfusion->hc }}</small></td>
                                        <td>{{ $transfusion->tipo_componente }}<br><small>{{ $transfusion->grupo_rh }}</small></td>
                                        <td>{{ $transfusion->codigo_bolsa }}<br><small>Vence {{ $transfusion->fecha_vencimiento }}</small></td>
                                        <td>{{ $transfusion->fecha_hora }}</td>
                                        <td>{{ $transfusion->volumen_adm }} ml</td>
                                        <td>
                                            <form method="POST" action="{{ route('enfermeria.transfusiones.aplicar', ['transfusionId' => $transfusion->transfusion_id]) }}">
                                                @csrf
                                                <label class="sr-only" for="hora-transfusion-{{ $transfusion->transfusion_id }}">Hora real</label>
                                                <input class="form-control form-control-sm mb-1" id="hora-transfusion-{{ $transfusion->transfusion_id }}" name="fecha_hora_aplicacion" type="datetime-local" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                                <select class="form-control form-control-sm mb-1" name="sala_id" required>
                                                    <option value="">Sala</option>
                                                    @foreach($salas as $sala)
                                                        <option value="{{ $sala->sala_id }}">{{ $sala->nombre }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="custom-control custom-checkbox mb-1">
                                                    <input class="custom-control-input" id="identidad-transfusion-{{ $transfusion->transfusion_id }}" name="identidad_confirmada" type="checkbox" value="1" required>
                                                    <label class="custom-control-label" for="identidad-transfusion-{{ $transfusion->transfusion_id }}">Identidad verificada</label>
                                                </div>
                                                <button class="btn btn-sm btn-danger" type="submit">Confirmar aplicación</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">No hay transfusiones programadas para esta fecha.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-warning card-outline">
                            <div class="card-header"><h3 class="card-title">Observación de sala</h3></div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('enfermeria.observaciones.store') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="obs-sala">Sala</label>
                                        <select class="form-control" id="obs-sala" name="sala_id">
                                            <option value="">No especificada</option>
                                            @foreach($salas as $sala)<option value="{{ $sala->sala_id }}">{{ $sala->nombre }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="obs-paciente">Paciente (opcional)</label>
                                        <select class="form-control" id="obs-paciente" name="paciente_id">
                                            <option value="">Sin paciente asociado</option>
                                            @foreach($pacientes as $paciente)
                                                <option value="{{ $paciente->paciente_id }}">H.C. {{ $paciente->hc }} — {{ $paciente->nombres }} {{ $paciente->apellidos }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="obs-sesion">Sesión relacionada (opcional)</label>
                                        <select class="form-control" id="obs-sesion" name="sesion_id">
                                            <option value="">Ninguna</option>
                                            @foreach($sesionesParaRegistro as $sesion)
                                                <option value="{{ $sesion->sesion_id }}">Sesión {{ $sesion->sesion_id }} — H.C. {{ $sesion->hc }} — {{ $sesion->status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="obs-categoria">Categoría</label>
                                            <select class="form-control" id="obs-categoria" name="categoria" required>
                                                <option value="clinica">Clínica</option><option value="operativa">Operativa</option><option value="otra">Otra</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="obs-gravedad">Gravedad</label>
                                            <select class="form-control" id="obs-gravedad" name="gravedad" required>
                                                <option value="baja">Baja</option><option value="moderada">Moderada</option><option value="alta">Alta</option><option value="critica">Crítica</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="obs-fecha">Fecha y hora del evento</label>
                                        <input class="form-control" id="obs-fecha" name="fecha_hora" type="datetime-local" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="obs-descripcion">Descripción</label>
                                        <textarea class="form-control" id="obs-descripcion" name="descripcion" rows="3" maxlength="5000" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="obs-accion">Acción tomada</label>
                                        <textarea class="form-control" id="obs-accion" name="accion_tomada" rows="2" maxlength="5000"></textarea>
                                    </div>
                                    <button class="btn btn-warning" type="submit">Guardar observación</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card card-danger card-outline">
                            <div class="card-header"><h3 class="card-title">Registrar reacción adversa</h3></div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('enfermeria.reacciones.store') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="reaccion-paciente">Paciente</label>
                                        <select class="form-control" id="reaccion-paciente" name="paciente_id" required>
                                            <option value="">Seleccione</option>
                                            @foreach($pacientes as $paciente)
                                                <option value="{{ $paciente->paciente_id }}">H.C. {{ $paciente->hc }} — {{ $paciente->nombres }} {{ $paciente->apellidos }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="reaccion-sesion">Sesión asociada</label>
                                        <select class="form-control" id="reaccion-sesion" name="sesion_id">
                                            <option value="">No aplica</option>
                                            @foreach($sesionesParaRegistro as $sesion)
                                                <option value="{{ $sesion->sesion_id }}">Sesión {{ $sesion->sesion_id }} — H.C. {{ $sesion->hc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="reaccion-transfusion">Transfusión asociada</label>
                                        <select class="form-control" id="reaccion-transfusion" name="transfusion_id">
                                            <option value="">No aplica</option>
                                            @foreach($transfusionesParaRegistro as $transfusion)
                                                <option value="{{ $transfusion->transfusion_id }}" data-paciente="{{ $transfusion->paciente_id }}">Transfusión {{ $transfusion->transfusion_id }} — Lote {{ $transfusion->codigo_bolsa }} — H.C. {{ $transfusion->hc }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Seleccione una sesión o una transfusión, no ambas.</small>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="reaccion-categoria">Categoría</label>
                                            <select class="form-control" id="reaccion-categoria" name="categoria" required>
                                                <option value="medicamento">Medicamento</option><option value="hemocomponente">Hemocomponente</option><option value="otra">Otra</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="reaccion-gravedad">Gravedad</label>
                                            <select class="form-control" id="reaccion-gravedad" name="gravedad" required>
                                                <option value="baja">Baja</option><option value="moderada">Moderada</option><option value="alta">Alta</option><option value="critica">Crítica</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reaccion-fecha">Fecha y hora de inicio</label>
                                        <input class="form-control" id="reaccion-fecha" name="fecha_hora" type="datetime-local" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="reaccion-descripcion">Descripción y signos observados</label>
                                        <textarea class="form-control" id="reaccion-descripcion" name="descripcion" rows="3" maxlength="5000" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="reaccion-intervencion">Intervención realizada</label>
                                        <textarea class="form-control" id="reaccion-intervencion" name="intervencion" rows="2" maxlength="5000"></textarea>
                                    </div>
                                    <button class="btn btn-danger" type="submit">Guardar reacción adversa</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-secondary card-outline">
                    <div class="card-header"><h3 class="card-title">Observaciones recientes</h3></div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-striped">
                            <thead><tr><th>Fecha</th><th>Paciente / H.C.</th><th>Sala</th><th>Tipo / gravedad</th><th>Descripción</th></tr></thead>
                            <tbody>
                                @forelse($observaciones as $observacion)
                                    <tr>
                                        <td>{{ $observacion->fecha_hora }}</td>
                                        <td>{{ trim(($observacion->nombres ?? '') . ' ' . ($observacion->apellidos ?? '')) ?: 'General' }}<br>{{ $observacion->hc ? 'H.C. ' . $observacion->hc : '' }}</td>
                                        <td>{{ $observacion->sala ?? '—' }}</td>
                                        <td>{{ $observacion->categoria }} / {{ $observacion->gravedad }}</td>
                                        <td>{{ $observacion->descripcion }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Aún no hay observaciones registradas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="{{ asset('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/adminlte/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/js/enfermeria.js') }}"></script>
</body>
</html>
