<div class="container-fluid">
    <div class="card card-primary card-outline shadow-sm mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <h3 class="card-title mb-0">Consulta Médica</h3>
                </div>
                <p class="mb-0 text-muted">
                    <strong>Paciente: </strong><p id="nombrePaConReg"></p>
                </p>
                <p class="mb-0 text-muted">
                    <strong>Representante:</strong><p id="nombreRepConReg"></p>
                </p>
            </div>
            <div class="text-md-right">
                <div class="text-muted small">Historia Clínica</div>
                <p class="font-weight-bold h5 mb-0" id="hcConReg"></p>
            </div>
        </div>
    </div>

    <form action="{{ route('medico.consultas.registrar') }}" method="POST" id="formRegCon">
        @csrf

        <div id="seccion-consulta-registrar1">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Clasificación</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Tipo de consulta</label>
                                <span class="form-control bg-light text-muted" id="tipoConReg"></span>
                            </div>

                            <div class="form-group">
                                <label>Enfermedad</label>
                                <select name="enfConReg" id="enfConReg" class="form-control">
                                    <option value="" disabled selected>Seleccione la enfermedad</option>
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Signos vitales</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Peso (kg)</label>
                                    <input type="number" step="0.1" min="0" name="pesoConReg" id="pesoConReg" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Talla (cm)</label>
                                    <input type="number" step="0.1" min="0" name="tallaConReg" id="tallaConReg" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>SC</label>
                                    <input type="number" step="0.01" min="0" name="scConReg" id="scConReg" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>FC</label>
                                    <input type="number" step="0.1" min="0" name="fcConReg" id="fcConReg" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>FR</label>
                                    <input type="number" step="0.1" min="0" name="frConReg" id="frConReg" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Antecedentes y síntomas iniciales</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Antecedentes Personales</label>
                        <textarea name="antePersoConReg" id="antePersoConReg" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Antecedentes Familiares</label>
                        <textarea name="anteFamiConReg" id="anteFamiConReg" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Signos y Síntomas Iniciales</label>
                        <textarea name="signosConReg" id="signosConReg" rows="3" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-none" id="seccion-consulta-registrar2">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Evolución y plan</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Evolución Subjetiva</label>
                        <textarea name="subjConReg" id="subjConReg" rows="4" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Plan de Trabajo</label>
                        <textarea name="planConReg" id="planConReg" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Próxima Cita</label>
                        <input type="date" name="citaFechaConReg" id="citaFechaConReg" class="form-control">
                        <input type="time" name="citaHoraConReg" id="citaHoraConReg" class="form-control">
                    </div>
                    <input type="hidden" name="consultaReg" id="consultaReg">
                    <input type="hidden" name="usuConReg" id="usuConReg">
                </div>
            </div>
        </div>

        <div class="d-none" id="seccion-consulta-registrar3">
            @include('medico.pacientes.registrar')
        </div>

        <div class="d-none" id="seccion-consulta-registrar4">
            
        </div>

        <div class="text-right mb-4">
            <div id="btnPagsConReg">
                
            </div>
            <button type="button" class="btn btn-danger mr-2" id="btnSalirConReg">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Consulta</button>
        </div>
    </form>

        <div class="modal fade" id="registrandoModalCon" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary" role="status"></div>
                <h5 class="mt-3">Registrando consulta...</h5>
                <p class="text-muted">Por favor, no cierres la ventana.</p>
              </div>
            </div>
          </div>
        </div>
    </div>
