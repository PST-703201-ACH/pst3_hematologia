// Historia clínica: controlador de navegación, búsqueda, selección y pestañas.
// No contiene datos clínicos ni plantillas HTML; usa los módulos cargados antes de este archivo.
(function () {
    document.addEventListener('DOMContentLoaded', () => {
        const templates = window.PST3ClinicalHistoryTemplates;
        const demoPatients = window.PST3ClinicalHistoryData;
        if (!templates || !demoPatients) return;
        const historyButtons = document.querySelectorAll('#btnHistoriaClinica, #btnHistoriaClinicaMed');
        const backButton = document.getElementById('btnVolverHistoria');
        const searchInput = document.getElementById('buscarHistoriaPaciente');
        const resultsContainer = document.getElementById('resultadosHistoriaClinica');
        const resultsCount = document.getElementById('cantidadHistorias');
        const noResults = document.getElementById('sinResultadosHistoria');
        const detailContainer = document.getElementById('detalleHistoriaClinica');
        if (historyButtons.length === 0 || !backButton || !searchInput || !resultsContainer || !detailContainer) return;

const renderResults = (term = '') => {
    const normalizedTerm = term.trim().toLocaleLowerCase();
    const filteredPatients = window.PST3ClinicalHistoryData.filter((patient) => (
        `${patient.firstName} ${patient.lastName} ${patient.hc}`.toLocaleLowerCase().includes(normalizedTerm)
    ));

    resultsCount.textContent = filteredPatients.length;
    noResults.classList.toggle('d-none', filteredPatients.length > 0);

    resultsContainer.innerHTML = filteredPatients.map((patient) => `
        <button type="button" class="clinical-history-option${patient.id === selectedPatientId ? ' is-selected' : ''}"
            role="option" aria-selected="${patient.id === selectedPatientId}" data-patient-id="${templates.escapeHtml(patient.id)}">
            <span class="clinical-history-avatar" aria-hidden="true">${templates.escapeHtml(templates.initials(patient))}</span>
            <span class="clinical-history-option-copy">
                <span class="clinical-history-option-name">${templates.escapeHtml(`${patient.firstName} ${patient.lastName}`)}</span>
                <span class="clinical-history-option-hc">${templates.escapeHtml(patient.hc)}</span>
            </span>
        </button>
    `).join('');
};

let selectedPatientId = null;

historyButtons.forEach((historyButton) => historyButton.addEventListener('click', () => {
    if (typeof window.intercambiarVista === 'function') {
        window.intercambiarVista('seccion-historia-clinica');
    }

    renderResults(searchInput.value);
    const firstVisiblePatient = window.PST3ClinicalHistoryData.find((patient) => (
        `${patient.firstName} ${patient.lastName} ${patient.hc}`
            .toLocaleLowerCase()
            .includes(searchInput.value.trim().toLocaleLowerCase())
    ));

    if (firstVisiblePatient) {
        selectPatient(firstVisiblePatient.id);
    }
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
    renderResults(searchInput.value);
    const selectedIsVisible = Array.from(resultsContainer.querySelectorAll('[data-patient-id]'))
        .some((option) => option.dataset.patientId === selectedPatientId);

    if (!selectedIsVisible) {
        const firstVisibleOption = resultsContainer.querySelector('[data-patient-id]');
        if (firstVisibleOption) {
            selectPatient(firstVisibleOption.dataset.patientId);
        } else {
            selectedPatientId = null;
            detailContainer.innerHTML = `
                <div class="clinical-history-empty">
                    <span class="clinical-history-empty-icon"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <h4>No hay coincidencias</h4>
                    <p>Prueba con otro nombre o número de historia clínica.</p>
                </div>
            `;
        }
    }
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

function selectPatient(patientId) {
    const patient = window.PST3ClinicalHistoryData.find((item) => item.id === patientId);
    if (!patient) {
        return;
    }

    selectedPatientId = patientId;
    renderResults(searchInput.value);
    templates.renderPatient(patient, detailContainer);
}
    });
})();
