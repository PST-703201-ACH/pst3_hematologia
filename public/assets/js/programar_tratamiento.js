async function cargarFormularioTratamiento() {
    const contenedor = document.getElementById('contenedor-tratamientos-programar');
    const link = document.getElementById('btnProgramarTratamiento');

    if (!contenedor || !link) {
        return;
    }

    try {
        const response = await fetch(link.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (!response.ok) {
            throw new Error(`No se pudo cargar el formulario (HTTP ${response.status}).`);
        }

        contenedor.innerHTML = await response.text();
        inicializarFormularioTratamiento(contenedor);
    } catch (error) {
        contenedor.textContent = error.message;
        console.error(error);
    }
}

function inicializarFormularioTratamiento(contenedor) {
    const patient = contenedor.querySelector('#paciente_id');
    const consultation = contenedor.querySelector('#consulta_id');
    const treatment = contenedor.querySelector('#protocolo_tratamiento_id');
    const medication = contenedor.querySelector('#medicina_pro_id');

    if (patient && consultation) {
        const filterConsultations = () => {
            [...consultation.options].forEach(option => {
                if (option.value) {
                    option.hidden = patient.value !== '' && option.dataset.paciente !== patient.value;
                }
            });
            if (consultation.selectedOptions[0]?.hidden) consultation.value = '';
        };
        patient.addEventListener('change', filterConsultations);
        filterConsultations();
    }

    if (treatment && medication) {
        const filterMedications = () => {
            [...medication.options].forEach(option => {
                if (option.value) {
                    option.hidden = treatment.value !== ''
                        && option.dataset.protocolo !== treatment.selectedOptions[0]?.dataset.protocolo;
                }
            });
            if (medication.selectedOptions[0]?.hidden) medication.value = '';
        };
        treatment.addEventListener('change', filterMedications);
        filterMedications();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (new URLSearchParams(window.location.search).get('vista') === 'tratamientos') {
        cargarFormularioTratamiento();
    }
});
