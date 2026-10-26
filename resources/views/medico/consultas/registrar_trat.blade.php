<div class="row">
    <div class="col-md-12">
        <div class="card card-outline">
            <div class="card-header">
                <h3 class="card-title">Asignacion de tratamiento</h3>
            </div>
            @csrf
            <div class="card-body">
                <h4>Datos de tratamiento</h4>
                
                <h5>Protocolos</h5>
                <div class="row">
                    <div class="form-group">
                        <button type="button" class="btn btn-success" id="btnNuevoProt"><i class="fas fa-solid fa-plus"></i></button>

                        <div id="medsTrat" style="display: flex; flex-direction: row; gap: 20px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>