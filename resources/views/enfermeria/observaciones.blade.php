<div class="card card-secondary card-outline">
    <div class="card-header"><h3 class="card-title">Observaciones</h3></div>
    
    <div class="card-body">

        <div class="d-flex align-items-center justify-content-between mb-3">

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" onclick="listarObservacion()"><i class="fas fa-undo" aria-hidden="true"></i>
                    </button>

                </div>

                <div id="btnPagObs" class="d-flex gap-1"></div>
            </div>

            <form onsubmit="event.preventDefault();" class="d-flex align-items-center m-0" id="formBusquedaObs">
                <input
                    type="text" 
                    name="bus_obs" 
                    placeholder="Buscar..." 
                    class="form-control me-2" 
                    style="width: 250px;"
                    autocomplete="off" 
                >
                <input
                    type="date"
                    name="bus_fecha_obs"
                    placeholder="Seleccione una fecha" 
                    class="form-control me-2"
                    autocomplete="off" 
                >
                <select name="filtroGra_obs" class="form-control">
                    <option value="" class="text-secondary">Todas</option>
                    <option value="0" class="text-success">Baja</option>
                    <option value="1" class="text-warning">Media</option>
                    <option value="2" class="text-danger">Alta</option>
                </select>

                <button type="button" id="btnLimpiar_obs" class="btn btn-danger d-none">Limpiar</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Enfermero</th>
                        <th>Paciente / H.C.</th>
                        <th>Gravedad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaObservacion">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>