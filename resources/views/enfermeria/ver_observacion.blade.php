<div class="modal fade" id="modalDetalleObs" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header bg-light">
        <h5 class="modal-title">Detalles de observacion</h5>
        <button type="button" class="btn-close" data-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="container-fluid">
          <h5 class="text-muted d-block">Informacion general</h5>

          <div class="col-6">
              <small class="text-muted d-block">Fecha - Hora</small>
              <strong id="detalleObsFecha"></strong>
          </div>

          <div class="col-6">
              <small class="text-muted d-block">Enfermedad</small>
              <strong id="detalleObsEnf"></strong>
          </div>

          <h5 class="text-muted d-block">Informacion detallada</h5>
          <div class="row mb-3">
            <div class="col-12 mb-3">
              <small class="text-muted d-block">Descripcion</small>
              <strong id="detalleObsDescrip" class="d-block text-break" style="font-weight: normal;"></strong>
            </div>
            <div class="col-12 mb-3">
              <small class="text-muted d-block">Acciones tomadas</small>
              <strong id="detalleObsAcc" class="d-block text-break" style="font-weight: normal;"></strong>
            </div>
          </div>

          <h5 class="text-muted d-block">Paciente</h5>
          <div class="row mb-3">
            <div class="col-4">
              <small class="text-muted d-block">Numero H.C</small>
              <strong id="detalleObsPaHc"></strong>
            </div>
            <div class="col-4">
              <small class="text-muted d-block">Nombre</small>
              <strong id="detalleObsPaNombre"></strong>
            </div>
            <div class="col-4">
              <small class="text-muted d-block">Cedula de identidad</small>
              <strong id="detalleObsPaCedula"></strong>
            </div>
          </div>

          <div class="col-6">
              <small class="text-muted d-block">Gravedad del caso</small>
              <strong id="detalleObsGra"></strong>
          </div>
          <h5 class="text-muted d-block">Informacion de tratamiento</h5>
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th colspan="4" style="text-align: center;">Protocolo</th>
              </tr>
              <tr>
                <td colspan="4" style="text-align: center;">Nombre</td>
              </tr>
              <tr>
                <th>#</th>
                <th>Medicina</th>
                <th>Via de administracion</th>
                <th>Cumplimiento</th>
              </tr>
            </thead>
            <tbody id="cuerpoDetalleObsTrat">
              
            </tbody>
          </table>

        </div>
      </div>

      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>