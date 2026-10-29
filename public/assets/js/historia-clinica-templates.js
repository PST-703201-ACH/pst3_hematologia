// Historia clínica: plantillas y utilidades de presentación.
// Genera HTML escapado y separa la presentación del controlador y los datos.
(function () {
    const escapeHtml = (value) => String(value ?? 'No registrado')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const calculateAge = (birthDate) => {
        const [year, month, day] = birthDate.split('-').map(Number);
        const today = new Date();
        let age = today.getFullYear() - year;

        if (today.getMonth() + 1 < month || (today.getMonth() + 1 === month && today.getDate() < day)) {
            age -= 1;
        }

        return `${age} años (demostración)`;
    };

    const fieldLabels = {
        hospitalizations: 'Hospitalizaciones previas',
        allergies: 'Alergias',
        surgeries: 'Antecedentes quirúrgicos',
        trauma: 'Antecedentes traumáticos',
        epidemiological: 'Antecedentes epidemiológicos',
        consanguinity: 'Consanguinidad',
        siblings: 'Número de hermanos',
        hematologic: 'Patologías familiares',
        general: 'Condiciones generales',
        pallor: 'Grado de palidez',
        skinMucosa: 'Piel y mucosas',
        lymphNodes: 'Adenopatías',
        hepatomegaly: 'Hepatomegalia',
        splenomegaly: 'Esplenomegalia',
        masses: 'Tumoraciones',
        weight: 'Peso',
        height: 'Talla',
        surface: 'Superficie corporal (SC)',
        heartRate: 'Frecuencia cardíaca (FC)',
        respiratoryRate: 'Frecuencia respiratoria (FR)',
        protocol: 'Protocolo activo',
        cycle: 'Ciclo',
        sessions: 'Sesiones',
        transfusions: 'Transfusiones'
    };

    const initials = (patient) => `${patient.firstName.slice(0, 1)}${patient.lastName.slice(0, 1)}`.toUpperCase();

    const renderFields = (fields) => Object.entries(fields).map(([key, value]) => `
        <div class="clinical-history-field">
            <dt>${escapeHtml(fieldLabels[key] || key)}</dt>
            <dd>${escapeHtml(value)}</dd>
        </div>
    `).join('');

    const renderCard = (title, icon, fields, wide = false) => `
        <article class="clinical-history-card${wide ? ' clinical-history-card-wide' : ''}">
            <div class="clinical-history-card-head">
                <i class="${escapeHtml(icon)}" aria-hidden="true"></i>
                <span>${escapeHtml(title)}</span>
            </div>
            <div class="clinical-history-card-body">
                <dl class="clinical-history-fields">${renderFields(fields)}</dl>
            </div>
        </article>
    `;

    const renderChips = (title, icon, values) => `
        <article class="clinical-history-card clinical-history-card-wide">
            <div class="clinical-history-card-head">
                <i class="${escapeHtml(icon)}" aria-hidden="true"></i>
                <span>${escapeHtml(title)}</span>
            </div>
            <div class="clinical-history-chip-list">
                ${values.map((value) => `
                    <span class="clinical-history-chip">
                        <i class="fas fa-circle" aria-hidden="true"></i>${escapeHtml(value)}
                    </span>
                `).join('')}
            </div>
        </article>
    `;

    const renderControls = (controls) => {
        if (controls.length === 0) {
            return '<p class="text-muted mb-0">Esta ficha demostrativa no incluye consultas de control.</p>';
        }

        return `
            <div class="clinical-history-timeline">
                ${controls.map((control) => `
                    <article class="clinical-history-visit">
                        <div class="clinical-history-visit-head">
                            <strong>${escapeHtml(control.date)}</strong>
                            <span class="badge badge-info">${escapeHtml(control.type)}</span>
                        </div>
                        <p><strong>Evolución:</strong> ${escapeHtml(control.subjective)}</p>
                        <p><strong>Síntomas/eventos:</strong> ${escapeHtml(control.symptoms)}</p>
                        <p><strong>Examen y signos vitales:</strong> ${escapeHtml(control.vitals)} ${escapeHtml(control.exam)}</p>
                        <p><strong>Tratamiento:</strong> ${escapeHtml(control.treatment)}</p>
                        <p><strong>Laboratorios/procedimientos:</strong> ${escapeHtml(control.labs)}</p>
                        <p><strong>Plan de seguimiento:</strong> ${escapeHtml(control.plan)}</p>
                        <p class="mb-0"><strong>Próxima cita:</strong> ${escapeHtml(control.nextAppointment)}</p>
                    </article>
                `).join('')}
            </div>
        `;
    };

    const renderPatient = (patient, detailContainer) => {
        const name = `${patient.firstName} ${patient.lastName}`;
        const initial = patient.initial;

        detailContainer.innerHTML = `
            <div class="clinical-history-profile">
                <div class="clinical-history-profile-head">
                    <div class="clinical-history-profile-avatar" aria-hidden="true">${escapeHtml(initials(patient))}</div>
                    <div class="clinical-history-profile-title">
                        <h4>${escapeHtml(name)}</h4>
                        <p><i class="fas fa-map-marker-alt mr-1" aria-hidden="true"></i>${escapeHtml(patient.origin)}</p>
                    </div>
                    <span class="badge badge-secondary clinical-history-profile-state">${escapeHtml(patient.status)}</span>
                </div>
                <div class="clinical-history-profile-meta">
                    <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Historia clínica</span><span class="clinical-history-meta-value">${escapeHtml(patient.hc)}</span></div>
                    <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Edad / nacimiento</span><span class="clinical-history-meta-value">${escapeHtml(calculateAge(patient.birthDate))}</span></div>
                    <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Sexo</span><span class="clinical-history-meta-value">${escapeHtml(patient.sex)}</span></div>
                    <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Tipaje sanguíneo</span><span class="clinical-history-meta-value">${escapeHtml(patient.bloodType)}</span></div>
                </div>
            </div>

            <div class="clinical-history-tabs" role="tablist" aria-label="Secciones de la historia clínica">
                <button type="button" class="clinical-history-tab is-active" role="tab" aria-selected="true" aria-controls="panelResumenClinico" data-history-tab="panelResumenClinico">Resumen</button>
                <button type="button" class="clinical-history-tab" role="tab" aria-selected="false" aria-controls="panelPrimeraConsulta" data-history-tab="panelPrimeraConsulta">Primera consulta</button>
                <button type="button" class="clinical-history-tab" role="tab" aria-selected="false" aria-controls="panelEstudiosClinicos" data-history-tab="panelEstudiosClinicos">Laboratorios y estudios</button>
                <button type="button" class="clinical-history-tab" role="tab" aria-selected="false" aria-controls="panelSeguimientoClinico" data-history-tab="panelSeguimientoClinico">Controles y tratamiento</button>
            </div>

            <section class="clinical-history-panel" id="panelResumenClinico" role="tabpanel">
                <div class="clinical-history-cards">
                    ${renderCard('Datos de contacto', 'fas fa-address-card', {
                        'Teléfono': patient.phone,
                        'Procedencia': patient.origin,
                        'Fecha de nacimiento': patient.birthDate,
                        'Historia clínica': patient.hc
                    })}
                    ${renderCard('Representante legal', 'fas fa-user-friends', {
                        'Nombre': patient.representative.name,
                        'Cédula': patient.representative.id,
                        'Parentesco': patient.representative.relationship
                    })}
                    ${renderCard('Clasificación clínica', 'fas fa-clipboard-check', {
                        'Tipo de consulta': patient.classification.consultation,
                        'Tipo de patología (Hemopatía / tumor benigno / maligno)': patient.classification.pathology,
                        'Diagnóstico': patient.classification.diagnosis,
                        'Código CIE-10': patient.classification.cie10
                    }, true)}
                    ${renderCard('Primera consulta', 'fas fa-calendar-check', {
                        'Fecha': initial.date,
                        'Motivo de consulta': initial.reason,
                        'Próxima cita': initial.plan.nextAppointment
                    }, true)}
                    ${renderCard('Somatometría y signos vitales', 'fas fa-heartbeat', initial.vitals)}
                    ${renderCard('Plan de trabajo inicial', 'fas fa-tasks', {
                        'Medicación': initial.plan.medication,
                        'Laboratorios': initial.plan.requestedLabs,
                        'Interconsultas': initial.plan.referrals,
                        'Próxima cita': initial.plan.nextAppointment
                    })}
                </div>
            </section>

            <section class="clinical-history-panel" id="panelPrimeraConsulta" role="tabpanel" hidden>
                <div class="clinical-history-cards">
                    ${renderCard('Enfermedad actual / anamnesis', 'fas fa-comment-medical', {
                        'Fecha de consulta': initial.date,
                        'Motivo y síntomas iniciales': initial.reason
                    }, true)}
                    ${renderCard('Antecedentes personales', 'fas fa-notes-medical', initial.personalHistory)}
                    ${renderCard('Antecedentes familiares', 'fas fa-users', initial.familyHistory)}
                    ${renderChips('Signos y síntomas específicos', 'fas fa-stethoscope', initial.symptoms)}
                    ${renderCard('Evaluación física sistemática', 'fas fa-user-md', initial.exam, true)}
                    ${renderCard('Somatometría y signos vitales', 'fas fa-heartbeat', initial.vitals, true)}
                </div>
            </section>

            <section class="clinical-history-panel" id="panelEstudiosClinicos" role="tabpanel" hidden>
                <div class="clinical-history-cards">
                    ${renderCard('Hemograma y fórmula leucocitaria', 'fas fa-tint', initial.hemogram, true)}
                    ${renderCard('Química sanguínea y serologías', 'fas fa-vial', initial.chemistry, true)}
                    ${renderChips('Estudios especiales y procedimientos', 'fas fa-microscope', initial.specialStudies)}
                </div>
            </section>

            <section class="clinical-history-panel" id="panelSeguimientoClinico" role="tabpanel" hidden>
                <div class="clinical-history-cards mb-3">
                    ${renderCard('Tratamiento y protocolo activo', 'fas fa-capsules', patient.treatment, true)}
                    ${renderCard('Plan de seguimiento', 'fas fa-calendar-alt', {
                        'Medicación / ajustes': initial.plan.medication,
                        'Estudios pendientes': initial.plan.requestedLabs,
                        'Interconsultas': initial.plan.referrals,
                        'Próxima cita': initial.plan.nextAppointment
                    }, true)}
                </div>
                <div class="clinical-history-card clinical-history-card-wide">
                    <div class="clinical-history-card-head">
                        <i class="fas fa-history" aria-hidden="true"></i>
                        <span>Evolución y consultas de control</span>
                    </div>
                    <div class="clinical-history-card-body py-3">${renderControls(patient.controls)}</div>
                </div>
            </section>

            <p class="clinical-history-disclaimer mb-0">
                <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
                Maqueta frontend con datos ficticios. No usar para decisiones clínicas.
            </p>
        `;
    };


    window.PST3ClinicalHistoryTemplates = Object.freeze({
        escapeHtml, calculateAge, fieldLabels, initials, renderFields, renderCard, renderChips, renderControls, renderPatient
    });
})();
