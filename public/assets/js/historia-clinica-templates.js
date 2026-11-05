// Historia clínica: representa únicamente los datos clínicos principales enviados por el backend.
(function () {
    const escapeHtml = (value) => String(value ?? 'No registrado')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const displayValue = (value) => value === null || value === undefined || value === ''
        ? 'No registrado'
        : String(value);

    const formatDate = (value) => {
        if (!value) {
            return 'No registrada';
        }

        const [date, time] = String(value).replace('T', ' ').split(' ');
        const parts = date.split('-');
        const formattedDate = parts.length === 3 ? `${parts[2]}/${parts[1]}/${parts[0]}` : date;
        return time ? `${formattedDate} ${time.slice(0, 5)}` : formattedDate;
    };

    const calculateAge = (birthDate) => {
        if (!birthDate) {
            return 'No registrada';
        }

        const [year, month, day] = String(birthDate).slice(0, 10).split('-').map(Number);
        const today = new Date();
        let age = today.getFullYear() - year;

        if (today.getMonth() + 1 < month || (today.getMonth() + 1 === month && today.getDate() < day)) {
            age -= 1;
        }

        return `${age} años`;
    };

    const initials = (patient) => {
        const firstName = patient.persona?.nombres || '';
        const lastName = patient.persona?.apellidos || '';
        return `${firstName.slice(0, 1)}${lastName.slice(0, 1)}`.toUpperCase() || 'P';
    };

    const renderFields = (fields) => Object.entries(fields).map(([label, value]) => `
        <div class="clinical-history-field">
            <dt>${escapeHtml(label)}</dt>
            <dd>${escapeHtml(displayValue(value))}</dd>
        </div>
    `).join('');

    const renderCard = (title, icon, fields) => `
        <article class="clinical-history-card">
            <div class="clinical-history-card-head">
                <i class="${escapeHtml(icon)}" aria-hidden="true"></i>
                <span>${escapeHtml(title)}</span>
            </div>
            <div class="clinical-history-card-body">
                <dl class="clinical-history-fields">${renderFields(fields)}</dl>
            </div>
        </article>
    `;

    const renderConsultations = (consultations) => {
        if (consultations.length === 0) {
            return '<p class="text-muted mb-0">No hay consultas registradas para este paciente.</p>';
        }

        return `
            <div class="clinical-history-timeline">
                ${consultations.map((consultation) => `
                    <article class="clinical-history-visit">
                        <div class="clinical-history-visit-head">
                            <strong>${escapeHtml(formatDate(consultation.fecha_hora))}</strong>
                            <span class="badge badge-info">Tipo ${escapeHtml(displayValue(consultation.tipo))}</span>
                        </div>
                        <div class="clinical-history-cards">
                            ${renderCard('Diagnóstico y signos vitales', 'fas fa-heartbeat', {
                                'Enfermedad': consultation.enfermedad,
                                'Estado de consulta': consultation.status,
                                'Peso': consultation.peso,
                                'Talla': consultation.talla,
                                'Superficie corporal': consultation.sc,
                                'Frecuencia cardíaca': consultation.fc,
                                'Frecuencia respiratoria': consultation.fr
                            })}
                            ${renderCard('Anamnesis y evolución', 'fas fa-comment-medical', {
                                'Antecedentes personales': consultation.ante_personal,
                                'Antecedentes familiares': consultation.ante_familiar,
                                'Síntomas': consultation.sintomas,
                                'Evolución': consultation.subjetivo
                            })}
                            ${renderCard('Plan de trabajo', 'fas fa-tasks', {
                                'Plan': consultation.plan_trabajo,
                                'Próxima cita': formatDate(consultation.proxima_cita)
                            })}
                        </div>
                    </article>
                `).join('')}
            </div>
        `;
    };

    const renderPatient = (data, detailContainer) => {
        const patient = data.paciente;
        const persona = patient.persona || {};
        const name = `${persona.nombres || ''} ${persona.apellidos || ''}`.trim() || 'Paciente';
        const patientStatus = Number(patient.status) === 1 ? 'Activo' : Number(patient.status) === 0 ? 'Inactivo' : 'No informado';
        const statusClass = Number(patient.status) === 1 ? 'badge-success' : Number(patient.status) === 0 ? 'badge-danger' : 'badge-secondary';

        detailContainer.innerHTML = `
            <div class="clinical-history-profile">
                <div class="clinical-history-profile-head">
                    <div class="clinical-history-profile-avatar" aria-hidden="true">${escapeHtml(initials(patient))}</div>
                    <div class="clinical-history-profile-title">
                        <h4>${escapeHtml(name)}</h4>
                        <p><i class="fas fa-id-card mr-1" aria-hidden="true"></i> Cédula: ${escapeHtml(displayValue(persona.cedula))}</p>
                    </div>
                    <span class="badge ${statusClass} clinical-history-profile-state">${escapeHtml(patientStatus)}</span>
                </div>
                <div class="clinical-history-profile-meta">
                    <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Historia clínica</span><span class="clinical-history-meta-value">${escapeHtml(displayValue(patient.hc))}</span></div>
                    <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Edad / nacimiento</span><span class="clinical-history-meta-value">${escapeHtml(calculateAge(persona.fecha_nacimiento))}</span></div>
                    <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Sexo</span><span class="clinical-history-meta-value">${escapeHtml(displayValue(persona.sexo))}</span></div>
                    <div class="clinical-history-meta-item"><span class="clinical-history-meta-label">Teléfono</span><span class="clinical-history-meta-value">${escapeHtml(displayValue(persona.telefono))}</span></div>
                </div>
            </div>

            <div class="clinical-history-tabs" role="tablist" aria-label="Secciones de la historia clínica">
                <button type="button" class="clinical-history-tab is-active" role="tab" aria-selected="true" aria-controls="panelResumenClinico" data-history-tab="panelResumenClinico">Datos personales</button>
                <button type="button" class="clinical-history-tab" role="tab" aria-selected="false" aria-controls="panelConsultasClinicas" data-history-tab="panelConsultasClinicas">Consultas</button>
            </div>

            <section class="clinical-history-panel" id="panelResumenClinico" role="tabpanel">
                <div class="clinical-history-cards">
                    ${renderCard('Datos de contacto', 'fas fa-address-card', {
                        'Cédula': persona.cedula,
                        'Correo': persona.email,
                        'Dirección': persona.direccion_exacta,
                        'Fecha de nacimiento': formatDate(persona.fecha_nacimiento)
                    })}
                    ${renderCard('Ubicación', 'fas fa-map-marked-alt', {
                        'Estado': persona.estado,
                        'Municipio': persona.municipio,
                        'Parroquia': persona.parroquia
                    })}
                </div>
            </section>

            <section class="clinical-history-panel" id="panelConsultasClinicas" role="tabpanel" hidden>
                ${renderConsultations(data.consultas)}
            </section>
        `;
    };

    window.PST3ClinicalHistoryTemplates = Object.freeze({
        escapeHtml,
        formatDate,
        initials,
        renderPatient
    });
})();
