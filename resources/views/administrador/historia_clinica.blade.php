{{-- Historia clínica: contenedor Blade solo frontend con datos ficticios y controles de búsqueda/detalle. --}}
<div class="card card-primary card-outline clinical-history-page">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h3 class="card-title">
                <i class="fas fa-notes-medical mr-2 text-primary" aria-hidden="true"></i>
                Historia Clínica
            </h3>
            <small class="d-block text-muted mt-1">Consulta longitudinal del paciente</small>
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0" id="btnVolverHistoria">
            <i class="fas fa-arrow-left mr-1" aria-hidden="true"></i>
            Volver al listado
        </button>
    </div>

    <div class="card-body">
        <div class="alert alert-warning clinical-history-demo-alert" role="note">
            <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
            <strong>Vista de demostración:</strong> estos nombres, valores y registros son ficticios. No se consultan ni modifican datos reales.
        </div>

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
                <small class="form-text text-muted" id="ayudaBuscarHistoria">Escribe parte del nombre o de la historia clínica.</small>

                <div class="clinical-history-results-heading">
                    <span>Pacientes de ejemplo</span>
                    <span class="badge badge-light" id="cantidadHistorias">0</span>
                </div>
                <div id="resultadosHistoriaClinica" class="clinical-history-results" role="listbox" aria-label="Resultados de búsqueda"></div>
                <div id="sinResultadosHistoria" class="clinical-history-no-results d-none" role="status">
                    <i class="far fa-folder-open" aria-hidden="true"></i>
                    <span>No se encontraron coincidencias.</span>
                </div>
            </aside>
                
            <section id="detalleHistoriaClinica" class="clinical-history-detail" aria-live="polite" aria-label="Detalle de historia clínica">
                <div class="clinical-history-empty">
                    <span class="clinical-history-empty-icon"><i class="fas fa-user-injured" aria-hidden="true"></i></span>
                    <h4>Selecciona un paciente</h4>
                    <p>Elige una ficha de demostración para explorar sus datos de identificación, consulta inicial, estudios y controles.</p>
                </div>
            </section>
        </div>

        <!--
            Mapeo para conectar datos en una futura tarea backend:
            - Identificación: logistica.paciente (hc, status) + persona (nombres, apellidos,
              fecha_nacimiento, sexo, telefono, estado_id, municipio_id, parroquia_id).
            - Tipaje: desechable.tipaje_sanguineo (grupo_ab, factor_rh, fenotipo_extendido, fecha).
            - Representante: desechable.paciente_representante (parentesco) + representante/persona.
            - Consulta: logistica.consulta (fecha_hora, tipo, enfermedad_id, peso, talla, sc, fc, fr,
              ante_personal, ante_familiar, sintomas, subjetivo, plan_trabajo, proxima_cita).
            - Diagnóstico: logistica.enfermedad (descripcion). Confirmar la semántica de enfermedad.tipo
              para clasificar hemopatías/tumores y resolver la diferencia tipo/tipo_id de consulta;
              CIE-10 no aparece en el esquema consultado.
            - Laboratorios: desechable.orden_laboratorio, examen y resultado_laboratorio; frotis_fsp
              contiene parte de la fórmula leucocitaria. Confirmar campos disponibles para química,
              imágenes, médula ósea, citometría e inmunohistoquímica.
            - Signos sistemáticos, localización de sangrado, exploración física detallada y algunos
              antecedentes no tienen campos dedicados en el esquema consultado: definir el origen
              de esos datos antes de integrarlos.
            - Tratamiento y transfusiones: revisar las relaciones de protocolo/sesión y desechable.transfusion.
        -->
    </div>
</div>
