<div class="row">
    <div class="col-md-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">Registro de nueva medicina</h3>
            </div>
            
            <form action="{{ route('medicina.registrar') }}" id="formRegMed" method="POST">
                @csrf
                <div class="card-body">
                    <h4>Datos de medicina</h4>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="nombreMedReg">Nombre</label>
                                <input type="text" name="nombreMedReg" id="nombreMedReg" class="form-control" autocomplete="off" placeholder="Ingrese el nombre de la enfermedad">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="button" id="btnSalirRegMed" class="btn btn-danger">Salir</button>

                    <button type="submit" class="btn btn-success">Registrar medicina</button>
                </div>
                <div id="alertCreateMed"></div>
            </form>
            <div class="modal fade" id="registrandoModalMed" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <h5 class="mt-3">Registrando medicina...</h5>
                    <p class="text-muted">Por favor, no cierres la ventana.</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>