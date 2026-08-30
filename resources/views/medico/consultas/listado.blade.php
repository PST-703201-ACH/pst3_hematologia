<div class="card card-secondary card-outline">
	<div class="card-header">
		<h3 class="card-title">Consultas</h3>
	</div>

	<div class="card-body">
		<ul class="nav nav-tabs" id="tabCon">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" data-target="#tabConP" href="#">
                    <i class="fas fa-solid fa-circle mr-2"></i>Pendientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" data-target="#tabConR" href="#">
                    <i class="fas fa-solid fa-circle-check"></i>Realizadas
                </a>
            </li>
        </ul>

        <div class="tab-content">
        	<div class="tab-pane fade show active" id="tabConP">
                <div class="table-responsive">
                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" onclick="listarConsultaP()"><i class="fas fa-undo" aria-hidden="true"></i>
                                </button>
                            </div>
                            
                            <div id="btnPagConP" class="d-flex gap-1"></div>
                        </div>

                        <form onsubmit="event.preventDefault();" class="d-flex align-items-center m-0" id="busquedaConP">
                            <input
                                type="text" 
                                name="busqueda_conp"
                                placeholder="Buscar..." 
                                class="form-control me-2" 
                                style="width: 250px;"
                                autocomplete="off" 
                            >
                            <input
                                type="date"
                                name="busqueda_fechaP"
                                placeholder="Seleccione una fecha" 
                                class="form-control me-2"
                                autocomplete="off"
                            >
                            <button type="button" id="btnLimpiar_conp" class="btn btn-danger d-none">Limpiar</button>
                        </form>
                    </div>
                    <table class="card-table table">
                        <caption>Consultas pendientes</caption>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Paciente</th>
                                <th scope="col">Nº de H.C</th>
                                <th scope="col">Fecha y hora de atencion</th>
                                <th scope="col">Enfermedad</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaConsultasP">

                        </tbody>
                    </table>
                </div>
        	</div>

        	<div class="tab-pane fade" id="tabConR">
        		<div class="table-responsive">
                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" onclick="listarConsultaR()"><i class="fas fa-undo" aria-hidden="true"></i>
                                </button>
                            </div>
                            
                            <div id="btnPagConR" class="d-flex gap-1"></div>
                        </div>

                        <form onsubmit="event.preventDefault();" class="d-flex align-items-center m-0" id="busquedaConR">
                            <input
                                type="text" 
                                name="busqueda_conr"
                                placeholder="Buscar..." 
                                class="form-control me-2" 
                                style="width: 250px;"
                                autocomplete="off" 
                            >

                            <button type="button" id="btnLimpiar_conr" class="btn btn-danger d-none">Limpiar</button>
                        </form>
                    </div>
                    <table class="card-table table">
                        <caption>Consultas realizadas</caption>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Stock</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaConsultasR">

                        </tbody>
                    </table>
                </div>
        	</div>
        </div>
	</div>
</div>