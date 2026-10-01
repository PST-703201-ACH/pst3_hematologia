<div class="row">
    <div class="col-md-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">Actualizacion de medicina</h3>
            </div>
            
            <form action="{{ route('medicina.actualizar') }}" id="formUpMed" method="POST">
                @csrf
                <div class="card-body">
                    <h4>Datos de medicina</h4>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="nombreMedUp">Nombre</label>
                                <input type="text" name="nombreMedUp" id="nombreMedUp" class="form-control" autocomplete="off" placeholder="Ingrese el nombre de la medicina">
                                <input type="hidden" name="medicinaUp" id="medicinaUp">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="button" id="btnSalirUpMed" class="btn btn-danger">Salir</button>

                    <button type="submit" class="btn btn-success">Actualizar medicina</button>
                </div>
                <div id="alertUpdateMed"></div>
            </form>
            <div class="modal fade" id="actualizandoModalMed" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <h5 class="mt-3">Actualizando medicina...</h5>
                    <p class="text-muted">Por favor, no cierres la ventana.</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>