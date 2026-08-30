<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Editar enfermedad</h3>
            </div>
            
            <form action="{{ route('enfermedad.actualizar') }}" id="formUpEnf" method="POST">
                @csrf
                <div class="card-body">
                    <h4>Datos de enfermedad</h4>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="nombreEnfUp">Nombre</label>
                                <input type="text" name="nombreEnfUp" id="nombreEnfUp" class="form-control" autocomplete="off" placeholder="Ingrese el nombre de la enfermedad">
                                <input type="hidden" name="enfermedadUp" id="enfermedadUp">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="tipEnfUp">Tipo</label>
                                <select name="tipEnfUp" id="tipEnfUp" class="form-control" autocomplete="off">
                                    <option disabled selected value="">Seleccione el tipo de enfermedad</option>
                                    <option class="text-warning" value="1">Benigna</option>
                                    <option class="text-danger" value="2">Maligna</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="button" id="btnSalirUpEnf" class="btn btn-danger">Salir</button>

                    <button type="submit" class="btn btn-success">Actualizar enfermedad</button>
                </div>
                <div id="alertUpdateEnf"></div>
            </form>
            <div class="modal fade" id="actualizandoModalEnf" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <h5 class="mt-3">Actualizando enfermedad...</h5>
                    <p class="text-muted">Por favor, no cierres la ventana.</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>