{{-- Contenedor de búsqueda e historial clínico dentro del panel del Médico. --}}
<div class="card card-primary card-outline clinical-history-page">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <h3 class="card-title">
            <i class="fas fa-notes-medical mr-2 text-primary" aria-hidden="true"></i>
            Historia Clínica
        </h3>
        <button type="button" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0" id="btnVolverHistoria">
            <i class="fas fa-arrow-left mr-1" aria-hidden="true"></i>
            Volver al listado
        </button>
    </div>

    <div class="card-body"
        data-patients-url="{{ route('medico.historia-clinica.pacientes') }}"
        data-detail-url="{{ route('medico.historia-clinica.detalle', ['pacienteId' => '__PACIENTE__']) }}">
        <div class="clinical-history-workspace">
            <aside class="clinical-history-search" aria-label="Buscar paciente">
                <label for="buscarHistoriaPaciente">Buscar paciente</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
                    </div>
                    <input
                        type="search"
                        class="form-control"
                        id="buscarHistoriaPaciente"
                        placeholder="Nombre o número HC"
                        autocomplete="off"
                        aria-describedby="ayudaBuscarHistoria"
                    >
                </div>
                <small class="form-text text-muted" id="ayudaBuscarHistoria">Escribe parte del nombre o de la historia clínica. Se muestran hasta 50 coincidencias.</small>

                <div class="clinical-history-results-heading">
                    <span>Pacientes</span>
                    <span class="badge badge-light" id="cantidadHistorias">0</span>
                </div>
                <div id="resultadosHistoriaClinica" class="clinical-history-results" role="listbox" aria-label="Resultados de búsqueda"></div>
                <div id="sinResultadosHistoria" class="clinical-history-no-results d-none" role="status">
                    <i class="far fa-folder-open" aria-hidden="true"></i>
                    <span>No se encontraron coincidencias.</span>
                </div>
                <div id="errorHistoriaClinica" class="alert alert-danger d-none mt-3 mb-0" role="alert"></div>
            </aside>

            <section id="detalleHistoriaClinica" class="clinical-history-detail" aria-live="polite" aria-label="Detalle de historia clínica">
                <div class="clinical-history-empty">
                    <span class="clinical-history-empty-icon"><i class="fas fa-user-injured" aria-hidden="true"></i></span>
                    <h4>Selecciona un paciente</h4>
                    <p>Elige un paciente para consultar sus datos de identificación, consultas y registros clínicos disponibles.</p>
                </div>
            </section>
        </div>
    </div>
</div>
