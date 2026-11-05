// Historia clínica: busca pacientes y carga sus registros desde endpoints autorizados por rol.
(function () {
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('.clinical-history-page .card-body[data-patients-url]');
        const templates = window.PST3ClinicalHistoryTemplates;
        const historyButtons = document.querySelectorAll('#btnHistoriaClinica, #btnHistoriaClinicaMed');

        if (!container || !templates || historyButtons.length === 0) {
            return;
        }

        const backButton = document.getElementById('btnVolverHistoria');
        const searchInput = document.getElementById('buscarHistoriaPaciente');
        const resultsContainer = document.getElementById('resultadosHistoriaClinica');
        const resultsCount = document.getElementById('cantidadHistorias');
        const noResults = document.getElementById('sinResultadosHistoria');
        const errorMessage = document.getElementById('errorHistoriaClinica');
        const detailContainer = document.getElementById('detalleHistoriaClinica');
        const patientsUrl = container.dataset.patientsUrl;
        const detailUrl = container.dataset.detailUrl;

        if (!backButton || !searchInput || !resultsContainer || !resultsCount || !noResults || !errorMessage || !detailContainer) {
            return;
        }

        let selectedPatientId = null;
        let currentPatients = [];
        let currentTotal = 0;
        let searchTimer = null;
        let patientsRequest = null;
        let patientsRequestId = 0;
        let detailRequestId = 0;

        const setError = (message) => {
            errorMessage.textContent = message;
            errorMessage.classList.toggle('d-none', !message);
        };

        const requestJson = async (url, signal) => {
            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
                signal
            });

            if (!response.ok) {
                throw new Error(`La solicitud no pudo completarse (HTTP ${response.status}).`);
            }

            return response.json();
        };

        const renderResults = (patients, total) => {
            currentPatients = patients;
            currentTotal = total;
            resultsCount.textContent = total > patients.length
                ? `${patients.length} de ${total}`
                : String(patients.length);
            noResults.classList.toggle('d-none', patients.length > 0);
            resultsContainer.innerHTML = patients.map((patient) => `
                <button type="button" class="clinical-history-option${String(patient.paciente_id) === selectedPatientId ? ' is-selected' : ''}"
                    role="option" aria-selected="${String(patient.paciente_id) === selectedPatientId}"
                    data-patient-id="${templates.escapeHtml(patient.paciente_id)}">
                    <span class="clinical-history-avatar" aria-hidden="true">${templates.escapeHtml(templates.initials({ persona: { nombres: patient.nombre.split(' ')[0], apellidos: patient.nombre.split(' ').slice(1).join(' ') } }))}</span>
                    <span class="clinical-history-option-copy">
                        <span class="clinical-history-option-name">${templates.escapeHtml(patient.nombre)}</span>
                        <span class="clinical-history-option-hc">HC ${templates.escapeHtml(patient.hc)}</span>
                    </span>
                </button>
            `).join('');
        };

        const showEmptyDetail = (title, message) => {
            detailContainer.innerHTML = `
                <div class="clinical-history-empty">
                    <span class="clinical-history-empty-icon"><i class="fas fa-info-circle" aria-hidden="true"></i></span>
                    <h4>${templates.escapeHtml(title)}</h4>
                    <p>${templates.escapeHtml(message)}</p>
                </div>
            `;
        };

        const selectPatient = async (patientId) => {
            selectedPatientId = String(patientId);
            renderResults(currentPatients, currentTotal);
            detailContainer.setAttribute('aria-busy', 'true');
            showEmptyDetail('Cargando historia clínica', 'Consultando los registros disponibles del paciente.');
            setError('');

            const requestId = ++detailRequestId;
            const url = detailUrl.replace('__PACIENTE__', encodeURIComponent(patientId));

            try {
                const data = await requestJson(url);
                if (requestId === detailRequestId) {
                    templates.renderPatient(data, detailContainer);
                }
            } catch (error) {
                if (requestId === detailRequestId) {
                    showEmptyDetail('No se pudo cargar la historia', 'Intenta nuevamente o contacta al administrador del sistema.');
                    setError(error.message);
                }
            } finally {
                if (requestId === detailRequestId) {
                    detailContainer.setAttribute('aria-busy', 'false');
                }
            }
        };

        const loadPatients = async (term = '') => {
            patientsRequest?.abort();
            patientsRequest = new AbortController();
            const requestId = ++patientsRequestId;
            setError('');
            resultsContainer.setAttribute('aria-busy', 'true');

            const url = new URL(patientsUrl, window.location.href);
            if (term.trim()) {
                url.searchParams.set('q', term.trim());
            }

            try {
                const response = await requestJson(url, patientsRequest.signal);
                if (requestId !== patientsRequestId) {
                    return;
                }

                renderResults(response.data, response.total);

                if (response.data.length === 0) {
                    selectedPatientId = null;
                    detailRequestId += 1;
                    detailContainer.setAttribute('aria-busy', 'false');
                    showEmptyDetail('No hay coincidencias', 'Prueba con otro nombre o número de historia clínica.');
                } else {
                    const selected = response.data.find((patient) => String(patient.paciente_id) === selectedPatientId);
                    const patient = selected || response.data[0];
                    if (!selected) {
                        await selectPatient(patient.paciente_id);
                    }
                }
            } catch (error) {
                if (requestId === patientsRequestId && error.name !== 'AbortError') {
                    selectedPatientId = null;
                    detailRequestId += 1;
                    resultsContainer.replaceChildren();
                    resultsCount.textContent = '0';
                    noResults.classList.add('d-none');
                    showEmptyDetail('No se pudo cargar la lista', 'Intenta nuevamente o contacta al administrador del sistema.');
                    setError(error.message);
                }
            } finally {
                if (requestId === patientsRequestId) {
                    resultsContainer.setAttribute('aria-busy', 'false');
                }
            }
        };

        historyButtons.forEach((button) => button.addEventListener('click', (event) => {
            event.preventDefault();
            if (typeof window.intercambiarVista === 'function') {
                window.intercambiarVista('seccion-historia-clinica');
            }

            selectedPatientId = null;
            detailRequestId += 1;
            searchInput.value = '';
            loadPatients();
        }));

        backButton.addEventListener('click', () => {
            if (typeof window.intercambiarVista === 'function') {
                const returnView = document.getElementById('seccion-pacientes_listados')
                    ? 'seccion-pacientes_listados'
                    : 'seccion-usuarios';
                window.intercambiarVista(returnView);
            }
        });

        searchInput.addEventListener('input', () => {
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(() => loadPatients(searchInput.value), 250);
        });

        resultsContainer.addEventListener('click', (event) => {
            const option = event.target.closest('[data-patient-id]');
            if (option) {
                selectPatient(option.dataset.patientId);
            }
        });

        detailContainer.addEventListener('click', (event) => {
            const tab = event.target.closest('[data-history-tab]');
            if (!tab) {
                return;
            }

            detailContainer.querySelectorAll('[data-history-tab]').forEach((button) => {
                const isActive = button === tab;
                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-selected', String(isActive));
                button.tabIndex = isActive ? 0 : -1;
            });

            detailContainer.querySelectorAll('.clinical-history-panel').forEach((panel) => {
                panel.hidden = panel.id !== tab.dataset.historyTab;
            });
        });
    });
})();
