<div class="row">
    <div class="col-md-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">Registro de nuevo protocolo</h3>
            </div>
            
            <form action="{{ route('protocolo.registrar') }}" id="formRegProt" method="POST">
                @csrf
                <div class="card-body">
                    <h4>Datos de protocolo</h4>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="nombreProtReg">Nombre</label>
                                <input type="text" name="nombreProtReg" id="nombreProtReg" class="form-control" autocomplete="off" placeholder="Ingrese el nombre del protocolo">
                            </div>
                        </div>
                    </div>
                    <h5>Fases de protocolo</h5>
                    <div class="row">
                        <div class="form-group">
                            <button type="button" class="btn btn-success" id="btnNuevaFase"><i class="fas fa-solid fa-plus"></i></button>

                        <div id="fasesProt" style="display: flex; flex-direction: row; gap: 20px;">
                        </div>
                            
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="button" id="btnSalirRegProt" class="btn btn-danger">Salir</button>

                    <button type="submit" class="btn btn-success">Registrar protocolo</button>
                </div>
                <div id="alertCreateProt"></div>
            </form>
            <div class="modal fade" id="registrandoModalProt" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <h5 class="mt-3">Registrando protocolo...</h5>
                    <p class="text-muted">Por favor, no cierres la ventana.</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>