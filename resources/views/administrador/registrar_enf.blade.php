<div class="modal fade" id="modalEnfermedad" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Registrar enfermedad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('enfermedad.registrar') }}" id="formRegEnf" method="POST">
                @csrf
                <div class="modal-body">
                    <h4 style="text-align: center;" class="mb-4">DATOS DE ENFERMEDAD</h4>
                    <div class="row">   
                        <div class="form-group col-md-6 mb-3">
                            <label for="nombreEnfReg">Nombre de la enfermedad</label>
                            <input type="text" class="form-control" name="nombreEnfReg" id="nombreEnfReg" placeholder="Ingrese el nombre de la nueva enfermedad" autocomplete="off">
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="tipEnfReg">Tipo</label>
                            <select name="tipEnfReg" id="tipEnfReg" class="form-control" autocomplete="off">
                                <option disabled selected value="">Seleccione el tipo de enfermedad</option>
                                <option class="text-warning" value="1">Benigna</option>
                                <option class="text-danger" value="2">Maligna</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="alertCreateEnf" class="mt-3"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnSalirRegEnf" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Salir
                    </button>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="registrandoModalEnf" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary" role="status"></div>
                <h5 class="mt-3">Registrando enfermedad...</h5>
                <p class="text-muted mb-0">Por favor, no cierres la ventana.</p>
            </div>
        </div>
    </div>
</div>
