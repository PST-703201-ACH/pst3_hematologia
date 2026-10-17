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

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="jornada-enfermeria" role="tabpanel">
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
                                <div class="form-group col-md-4">
                                    <label for="filtro-paciente">Paciente / H.C.</label>
                                    <input class="form-control" id="filtro-paciente" name="paciente" value="{{ request('paciente') }}" maxlength="100">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filtro-estado">Status</label>
                                    <select class="form-control" id="filtro-estado" name="status">
                                        <option value="">Todos</option>
                                        @foreach(['Cumplida', 'Omitida'] as $estado)
                                            <option value="{{ $estado }}" @selected(request('status') === $estado)>{{ $estado }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
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
                                        <th>Vía de administración</th><th>Status</th><th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpo-sesiones-enfermeria">
                                    @forelse($sesionesProgramadas as $sesion)
                                        <tr>
                                            <td>{{ $sesion->fecha_hora }}</td>
                                            <td>{{ $sesion->nombres }} {{ $sesion->apellidos }}<br><small>H.C. {{ $sesion->hc }}</small></td>
                                            <td>{{ $sesion->protocolo }}<br><small>{{ $sesion->medicamento }} — Ciclo {{ $sesion->numero_ciclo }}</small></td>
                                            <td>{{ $sesion->via_administracion }}</td>
                                            <td>{{ $sesion->status }}</td>
                                            <td></td>
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
                    </div>

                    <div class="tab-pane fade" id="sala-enfermeria" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-warning card-outline">
                            <div class="card-header"><h3 class="card-title">Observación de sala</h3></div>
                            <div class="card-body">
                                <p class="text-muted">Formulario visual con datos estáticos de ejemplo.</p>
                                <form>
                                    <div class="form-group">
                                        <label for="obs-paciente">Paciente (opcional)</label>
                                        <select class="form-control" id="obs-paciente" name="paciente_id">
                                            <option value="">Sin paciente asociado</option>
                                            <option value="ejemplo">Paciente de ejemplo — H.C. 000000</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="obs-gravedad">Gravedad</label>
                                        <select class="form-control" id="obs-gravedad" name="gravedad">
                                            <option value="baja">Baja</option>
                                            <option value="moderada">Moderada</option>
                                            <option value="alta">Alta</option>
                                            <option value="critica">Crítica</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="obs-descripcion">Descripción</label>
                                        <textarea class="form-control" id="obs-descripcion" name="descripcion" rows="3" maxlength="5000" placeholder="Escriba una observación de ejemplo"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="obs-accion">Acción tomada</label>
                                        <textarea class="form-control" id="obs-accion" name="accion_tomada" rows="2" maxlength="5000" placeholder="Escriba una acción de ejemplo"></textarea>
                                    </div>
                                    <button class="btn btn-warning" type="button">Guardar observación</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-secondary card-outline">
                    <div class="card-header"><h3 class="card-title">Recomendaciones</h3></div>
                    <div class="card-body table-responsive">
                        <p class="text-muted">Datos estáticos de ejemplo.</p>
                        <table class="table table-sm table-striped">
                            <thead><tr><th>Fecha</th><th>Paciente / H.C.</th><th>Gravedad</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td>Ejemplo</td>
                                    <td>Paciente de ejemplo — H.C. 000000</td>
                                    <td>Baja</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
