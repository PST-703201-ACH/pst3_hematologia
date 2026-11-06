{{-- Detalle clínico HTML renderizado por Laravel para mantener la presentación en Blade. --}}
@php
    $persona = $patient->persona;
    $patientName = trim(($persona?->nombres ?? '') . ' ' . ($persona?->apellidos ?? '')) ?: 'Paciente';
    $patientStatus = (int) $patient->status === 1 ? 'Activo' : ((int) $patient->status === 0 ? 'Inactivo' : 'No informado');
    $patientStatusClass = (int) $patient->status === 1 ? 'badge-success' : ((int) $patient->status === 0 ? 'badge-danger' : 'badge-secondary');
    $initials = strtoupper(substr($persona?->nombres ?? '', 0, 1) . substr($persona?->apellidos ?? '', 0, 1)) ?: 'P';
    $age = $persona?->fecha_nacimiento
        ? \Illuminate\Support\Carbon::parse($persona->fecha_nacimiento)->age . ' años'
        : 'No registrada';
    $consultationsPerPage = 5;
    $consultationPages = (int) ceil($consultations->count() / $consultationsPerPage);
@endphp

<div class="clinical-history-profile">
    <div class="clinical-history-profile-head">
        <div class="clinical-history-profile-avatar" aria-hidden="true">{{ $initials }}</div>
        <div class="clinical-history-profile-title">
            <h4>{{ $patientName }}</h4>
            <p><i class="fas fa-id-card mr-1" aria-hidden="true"></i> Cédula: {{ $persona?->cedula ?? 'No registrado' }}</p>
        </div>
        <span class="badge {{ $patientStatusClass }} clinical-history-profile-state">{{ $patientStatus }}</span>
    </div>
    <div class="clinical-history-profile-meta">
        <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Historia clínica</span><span class="clinical-history-meta-value">{{ $patient->hc ?? 'No registrada' }}</span></div>
        <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Edad / nacimiento</span><span class="clinical-history-meta-value">{{ $age }}</span></div>
        <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Sexo</span><span class="clinical-history-meta-value">{{ $persona?->sexo ?? 'No registrado' }}</span></div>
        <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Teléfono</span><span class="clinical-history-meta-value">{{ $persona?->telefono ?? 'No registrado' }}</span></div>
    </div>
</div>

<div class="clinical-history-tabs" role="tablist" aria-label="Secciones de la historia clínica">
    <button type="button" class="clinical-history-tab is-active" role="tab" aria-selected="true" aria-controls="panelResumenClinico" data-history-tab="panelResumenClinico">Datos personales</button>
    <button type="button" class="clinical-history-tab" role="tab" aria-selected="false" aria-controls="panelConsultasClinicas" data-history-tab="panelConsultasClinicas">Consultas</button>
</div>

<section class="clinical-history-panel" id="panelResumenClinico" role="tabpanel">
    <div class="clinical-history-cards">
        @foreach([
            'Datos de contacto' => [
                'icon' => 'fas fa-address-card',
                'fields' => [
                    'Cédula' => $persona?->cedula,
                    'Correo' => $persona?->email,
                    'Dirección' => $persona?->direccion_exacta,
                    'Fecha de nacimiento' => $persona?->fecha_nacimiento
                        ? \Illuminate\Support\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y')
                        : 'No registrada',
                ],
            ],
            'Ubicación' => [
                'icon' => 'fas fa-map-marked-alt',
                'fields' => [
                    'Estado' => $persona?->estado?->nombre,
                    'Municipio' => $persona?->municipio?->nombre,
                    'Parroquia' => $persona?->parroquia?->nombre,
                ],
            ],
        ] as $title => $card)
            <article class="clinical-history-card">
                <div class="clinical-history-card-head">
                    <i class="{{ $card['icon'] }}" aria-hidden="true"></i>
                    <span>{{ $title }}</span>
                </div>
                <div class="clinical-history-card-body">
                    <dl class="clinical-history-fields">
                        @foreach($card['fields'] as $label => $value)
                            <div class="clinical-history-field">
                                <dt>{{ $label }}</dt>
                                <dd>{{ $value ?: 'No registrado' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="clinical-history-panel" id="panelConsultasClinicas" role="tabpanel" hidden>
    @if($consultations->isEmpty())
        <p class="text-muted mb-0">No hay consultas registradas para este paciente.</p>
    @else
        <div class="clinical-history-timeline" data-consultation-list>
            @foreach($consultations as $consultation)
                @php
                    $protocols = $protocolsByConsultation->get($consultation->consulta_id, []);
                    $fieldsByCard = [
                        'Diagnóstico y signos vitales' => [
                            'icon' => 'fas fa-heartbeat',
                            'fields' => [
                                'Enfermedad' => $consultation->enfermedad?->descripcion,
                                'Estado de consulta' => $consultation->status,
                                'Peso' => $consultation->peso,
                                'Talla' => $consultation->talla,
                                'Superficie corporal' => $consultation->sc,
                                'Frecuencia cardíaca' => $consultation->fc,
                                'Frecuencia respiratoria' => $consultation->fr,
                            ],
                        ],
                        'Anamnesis y evolución' => [
                            'icon' => 'fas fa-comment-medical',
                            'fields' => [
                                'Antecedentes personales' => $consultation->ante_personal,
                                'Antecedentes familiares' => $consultation->ante_familiar,
                                'Síntomas' => $consultation->sintomas,
                                'Evolución' => $consultation->subjetivo,
                            ],
                        ],
                        'Plan de trabajo' => [
                            'icon' => 'fas fa-tasks',
                            'fields' => [
                                'Plan' => $consultation->plan_trabajo,
                                'Próxima cita' => $consultation->proxima_cita
                                    ? \Illuminate\Support\Carbon::parse($consultation->proxima_cita)->format('d/m/Y')
                                    : 'No registrada',
                            ],
                        ],
                        'Protocolos asignados' => [
                            'icon' => 'fas fa-prescription-bottle-alt',
                            'fields' => [
                                'Protocolo(s)' => $protocols !== [] ? implode(', ', $protocols) : 'Sin protocolos registrados',
                            ],
                        ],
                    ];
                @endphp
                <article class="clinical-history-visit" data-consultation-item>
                    <div class="clinical-history-visit-head">
                        <strong>{{ $consultation->fecha_hora ? \Illuminate\Support\Carbon::parse($consultation->fecha_hora)->format('d/m/Y H:i') : 'Sin fecha' }}</strong>
                        <span class="badge badge-info">Tipo {{ $consultation->tipo ?? 'No registrado' }}</span>
                    </div>
                    <div class="clinical-history-cards">
                        @foreach($fieldsByCard as $title => $card)
                            <article class="clinical-history-card">
                                <div class="clinical-history-card-head">
                                    <i class="{{ $card['icon'] }}" aria-hidden="true"></i>
                                    <span>{{ $title }}</span>
                                </div>
                                <div class="clinical-history-card-body">
                                    <dl class="clinical-history-fields">
                                        @foreach($card['fields'] as $label => $value)
                                            <div class="clinical-history-field">
                                                <dt>{{ $label }}</dt>
                                                <dd>{{ $value !== null && $value !== '' ? $value : 'No registrado' }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>

        @if($consultationPages > 1)
            <nav class="clinical-history-pagination" aria-label="Paginación de consultas" data-consultation-pagination data-page-count="{{ $consultationPages }}">
                <button type="button" class="btn btn-outline-primary btn-sm" data-consultation-page="previous" disabled>
                    <i class="fas fa-chevron-left mr-1" aria-hidden="true"></i>Anterior
                </button>
                <span class="clinical-history-pagination-status" aria-live="polite" data-page-status>Página 1 de {{ $consultationPages }}</span>
                <button type="button" class="btn btn-outline-primary btn-sm" data-consultation-page="next">
                    Siguiente<i class="fas fa-chevron-right ml-1" aria-hidden="true"></i>
                </button>
            </nav>
        @endif
    @endif
</section>
