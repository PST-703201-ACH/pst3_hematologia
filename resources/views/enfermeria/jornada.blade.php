<div class="card card-primary card-outline">
    <div class="card-header"><h3 class="card-title">Tratamientos programados</h3></div>
    <div class="card-body">
        <form onsubmit="event.preventDefault();" class="w-100 m-0" id="formBusquedaJor">
            <div class="form-row align-items-end"> 
                
                <div class="form-group col-md-5">
                    <label for="filtro-paciente">Paciente / H.C</label>
                    <input class="form-control" name="busqueda_pac" maxlength="100">
                </div>
                
                <div class="form-group col-md-3">
                    <label for="filtro-estado">Status</label>
                    <select class="form-control" name="busqueda_status">
                        <option value="">Todos</option>
                    </select>
                </div>

                <div class="form-group d-none" id="contenedor-limpiar">
                    <button class="btn btn-danger btn-block" type="button" id="btnLimpiar_jor">Limpiar</button>
                </div>

                <div class="form-group col-md-1">
                    <button class="btn btn-primary btn-block" type="button" style="height: 38px;" onclick="listarJornada()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                
            </div>
        </form>



        <div class="table-responsive">

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                
                <div id="btnPagJor" class="d-flex gap-1"></div>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Paciente / H.C.</th>
                        <th>Status</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaJornada">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>