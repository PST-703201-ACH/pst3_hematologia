// Controla la búsqueda, selección y paginación del HTML de Historia Clínica renderizado por Laravel.
(function () {
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('.clinical-history-page .card-body[data-patients-url]');
        const historyButtons = document.querySelectorAll('#btnHistoriaClinica, #btnHistoriaClinicaMed');

        if (!container || historyButtons.length === 0) {
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

        const setEmptyDetail = (title, message) => {
            const empty = document.createElement('div');
            empty.className = 'clinical-history-empty';

            const icon = document.createElement('span');
            icon.className = 'clinical-history-empty-icon';
            icon.setAttribute('aria-hidden', 'true');
            const iconElement = document.createElement('i');
            iconElement.className = 'fas fa-info-circle';
            icon.append(iconElement);

            const heading = document.createElement('h4');
            heading.textContent = title;
            const description = document.createElement('p');
            description.textContent = message;

            empty.append(icon, heading, description);
            detailContainer.replaceChildren(empty);
        };

        const renderResults = (patients, total) => {
            currentPatients = patients;
            currentTotal = total;
            resultsCount.textContent = total > patients.length
                ? `${patients.length} de ${total}`
                : String(patients.length);
            noResults.classList.toggle('d-none', patients.length > 0);

            const fragment = document.createDocumentFragment();
            patients.forEach((patient) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `clinical-history-option${String(patient.paciente_id) === selectedPatientId ? ' is-selected' : ''}`;
                button.setAttribute('role', 'option');
                button.setAttribute('aria-selected', String(String(patient.paciente_id) === selectedPatientId));
                button.dataset.patientId = patient.paciente_id;

                const nameParts = patient.nombre.trim().split(/\s+/);
                const avatar = document.createElement('span');
                avatar.className = 'clinical-history-avatar';
                avatar.setAttribute('aria-hidden', 'true');
                avatar.textContent = `${nameParts[0]?.[0] || ''}${nameParts.length > 1 ? nameParts[nameParts.length - 1][0] : ''}`.toUpperCase() || 'P';

                const copy = document.createElement('span');
                copy.className = 'clinical-history-option-copy';
                const name = document.createElement('span');
                name.className = 'clinical-history-option-name';
                name.textContent = patient.nombre || 'Paciente';
                const historyNumber = document.createElement('span');
                historyNumber.className = 'clinical-history-option-hc';
                historyNumber.textContent = `HC ${patient.hc ?? 'No registrada'}`;

                copy.append(name, historyNumber);
                button.append(avatar, copy);
                fragment.append(button);
            });

            resultsContainer.replaceChildren(fragment);
        };

        const setConsultationPage = (page) => {
            const pagination = detailContainer.querySelector('[data-consultation-pagination]');
            const visits = [...detailContainer.querySelectorAll('[data-consultation-item]')];
            if (!pagination || visits.length === 0) {
                return;
            }

            const pageSize = 5;
            const pageCount = Number(pagination.dataset.pageCount);
            const currentPage = Math.min(Math.max(page, 1), pageCount);
            const firstIndex = (currentPage - 1) * pageSize;

            visits.forEach((visit, index) => {
                visit.hidden = index < firstIndex || index >= firstIndex + pageSize;
            });

            pagination.querySelector('[data-page-status]').textContent = `Página ${currentPage} de ${pageCount}`;
            pagination.querySelector('[data-consultation-page="previous"]').disabled = currentPage === 1;
            pagination.querySelector('[data-consultation-page="next"]').disabled = currentPage === pageCount;
            pagination.dataset.currentPage = String(currentPage);
        };

        const selectPatient = async (patientId) => {
            selectedPatientId = String(patientId);
            renderResults(currentPatients, currentTotal);
            detailContainer.setAttribute('aria-busy', 'true');
            setEmptyDetail('Cargando historia clínica', 'Consultando los registros disponibles del paciente.');
            setError('');

            const requestId = ++detailRequestId;
            const url = detailUrl.replace('__PACIENTE__', encodeURIComponent(patientId));

            try {
                const response = await fetch(url, {
                    headers: { Accept: 'text/html' },
                    credentials: 'same-origin'
                });
                if (!response.ok) {
                    throw new Error(`La solicitud no pudo completarse (HTTP ${response.status}).`);
                }

                const detailHtml = await response.text();
                if (requestId === detailRequestId) {
                    detailContainer.innerHTML = detailHtml;
                    setConsultationPage(1);
                }
            } catch (error) {
                if (requestId === detailRequestId) {
                    setEmptyDetail('No se pudo cargar la historia', 'Intenta nuevamente o contacta al administrador del sistema.');
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
                    setEmptyDetail('No hay coincidencias', 'Prueba con otro nombre o número de historia clínica.');
                } else {
                    const selected = response.data.find((patient) => String(patient.paciente_id) === selectedPatientId);
                    const patient = selected || response.data[0];
                    if (!selected) {
                        selectedPatientId = null;
                    }
                    await selectPatient(patient.paciente_id);
                }
            } catch (error) {
                if (requestId === patientsRequestId && error.name !== 'AbortError') {
                    selectedPatientId = null;
                    detailRequestId += 1;
                    resultsContainer.replaceChildren();
                    resultsCount.textContent = '0';
                    noResults.classList.add('d-none');
                    setEmptyDetail('No se pudo cargar la lista', 'Intenta nuevamente o contacta al administrador del sistema.');
                    setError(error.message);
                }
            } finally {
                if (requestId === patientsRequestId) {
                    resultsContainer.setAttribute('aria-busy', 'false');
                }
            }
        };

        const openPatientHistory = (patientId, patientName) => {
            if (!patientId || !patientName) {
                return;
            }

            if (typeof window.intercambiarVista === 'function') {
                window.intercambiarVista('seccion-historia-clinica');
            }

            selectedPatientId = String(patientId);
            searchInput.value = patientName;
            loadPatients(searchInput.value);
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

        document.addEventListener('click', (event) => {
            const historyButton = event.target.closest('.js-open-clinical-history');
            if (historyButton) {
                openPatientHistory(historyButton.dataset.pacienteId, historyButton.dataset.pacienteNombre);
            }
        });

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
            selectedPatientId = null;
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
            if (tab) {
                detailContainer.querySelectorAll('[data-history-tab]').forEach((button) => {
                    const isActive = button === tab;
                    button.classList.toggle('is-active', isActive);
                    button.setAttribute('aria-selected', String(isActive));
                    button.tabIndex = isActive ? 0 : -1;
                });

                detailContainer.querySelectorAll('.clinical-history-panel').forEach((panel) => {
                    panel.hidden = panel.id !== tab.dataset.historyTab;
                });
                return;
            }

            const pageButton = event.target.closest('[data-consultation-page]');
            if (pageButton && !pageButton.disabled) {
                const pagination = detailContainer.querySelector('[data-consultation-pagination]');
                const currentPage = Number(pagination.dataset.currentPage || 1);
                setConsultationPage(currentPage + (pageButton.dataset.consultationPage === 'next' ? 1 : -1));
            }
        });
    });
})();
