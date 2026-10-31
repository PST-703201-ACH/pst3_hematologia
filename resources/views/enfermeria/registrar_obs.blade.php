<div class="row">
    <div class="col-md-12">
        <div class="card card-warning card-outline">
            <div class="card-header"><h3 class="card-title">Revision de tratamiento</h3></div>
            <form action="{{ route('observacion.registrar') }}" id="formRegObs" method="POST">
                @csrf
                <div class="card-body">
                    <h4>Datos de Paciente</h4>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Paciente</label>
                                <span class="form-control" id="paObs"></span>
                                <input class="form-control" type="hidden" name="paObsReg" id="paObsReg" readonly>
                            </div>
                            <input type="hidden" name="usuObsReg">
                            <input type="hidden" name="tratObsReg">


                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Gravedad</label>
                                <select class="form-control" name="graObsReg">
                                    <option disabled selected value="">Seleccione la gravedad del caso</option>
                                    <option value="2" class="text-danger">Alta</option>
                                    <option value="1" class="text-warning">Media</option>
                                    <option value="0" class="text-success">Baja</option>
                                </select>
                                <small id="graObsReg" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea class="form-control" name="descripObsReg" rows="3" maxlength="5000" placeholder="Escriba su observacion"></textarea>
                                <small id="descripObsReg" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Acciónes tomadas</label>
                                <textarea class="form-control" name="accObsReg" rows="2" maxlength="5000" placeholder="Escriba las acciones realizadas"></textarea>
                                <small id="accObsReg" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    
                    <h5>Datos de tratamiento</h5>
                    <div class="row">
                        <div class="form-group">

                            <div id="medsObs" style="display: flex; flex-direction: row; gap: 20px;"></div>
                            
                        </div>
                    </div>
                </div>
                
                <div class="card-footer text-right">
                    <button type="button" id="btnSalirRegObs" class="btn btn-danger">Salir</button>

                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>

                <div id="alertRegObs"></div>
            </form>
            
            <div class="modal fade" id="registrandoModalObs" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <h5 class="mt-3">Registrando observacion...</h5>
                    <p class="text-muted">Por favor, no cierres la ventana.</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>