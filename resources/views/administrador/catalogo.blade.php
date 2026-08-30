<div class="card card-secondary card-outline">
	<div class="card-header">
		<h3 class="card-title">Catalogo medico</h3>
	</div>

	<div class="card-body">
		<ul class="nav nav-tabs" id="tabMenu">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" data-target="#tabProtocolos" href="#">
                    <i class="fas fa-solid fa-clipboard-list mr-2"></i>Protocolos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" data-target="#tabEnfermedades" href="#">
                    <i class="fas fa-solid fa-virus"></i>Enfermedades
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" data-target="#tabMedicamentos" href="#">
                    <i class="fas fa-solid fa-briefcase-medical"></i>Medicamentos
                </a>
            </li>
        </ul>

        <div class="tab-content">
        	<div class="tab-pane fade show active" id="tabProtocolos">
                <div class="table-responsive">
                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary"><i class="fas fa-undo" aria-hidden="true"></i>
                                </button>
                            </div>
                            
                            <div id="btnPagProt" class="d-flex gap-1"></div>
                        </div>

                        <form onsubmit="event.preventDefault();" class="d-flex align-items-center m-0" id="formBusquedaProt">
                            <input
                                type="text" 
                                name="busqueda_prot" 
                                placeholder="Buscar..." 
                                class="form-control me-2" 
                                style="width: 250px;"
                                autocomplete="off" 
                            >

                            <button type="button" id="btnLimpiar_prot" class="btn btn-danger d-none">Limpiar</button>
                        </form>
                    </div>
                    <table class="card-table table">
                        <caption>Protocolos medicos</caption>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaProtocolos">

                        </tbody>
                    </table>
                </div>
        	</div>

        	<div class="tab-pane fade" id="tabEnfermedades">
        		<div class="table-responsive">
                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex gap-2">
                                <button class="btn btn-success" id="btnRegEnf">Nueva</button>
                                <button class="btn btn-primary" onclick="listarEnfermedad()"><i class="fas fa-undo" aria-hidden="true"></i>
                                </button>
                            </div>
                            
                            <div id="btnPagEnf" class="d-flex gap-1"></div>
                        </div>

                        <form onsubmit="event.preventDefault();" class="d-flex align-items-center m-0" id="formBusquedaEnf">
                            <select name="filtroTip_enf" class="form-control">
                                <option value="" disabled selected>Todos los tipos de enfermedad</option>
                                
                                <option value="1" class="text-warning">Benigna</option>
                                <option value="2" class="text-danger">Maligna</option>
                            </select>

                            <input
                                type="text" 
                                name="busqueda_enf" 
                                placeholder="Buscar..." 
                                class="form-control me-2" 
                                style="width: 250px;"
                                autocomplete="off" 
                            >
                            <button type="button" id="btnLimpiar_enf" class="btn btn-danger d-none">Limpiar</button>
                        </form>
                    </div>

                    <table class="card-table table">
                        <caption>Enfermedades registradas</caption>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Tipo</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaEnfermedades">

                        </tbody>
                    </table>
                </div>
        	</div>

            <div class="tab-pane fade" id="tabMedicamentos">
                <div class="table-responsive">
                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary"><i class="fas fa-undo" aria-hidden="true"></i>
                                </button>
                            </div>
                            
                            <div id="btnPagMed" class="d-flex gap-1"></div>
                        </div>

                        <form onsubmit="event.preventDefault();" class="d-flex align-items-center m-0" id="formBusquedaMed">
                            <input
                                type="text" 
                                name="busqueda_med" 
                                placeholder="Buscar..." 
                                class="form-control me-2" 
                                style="width: 250px;"
                                autocomplete="off" 
                            >

                            <button type="button" id="btnLimpiar_med" class="btn btn-danger d-none">Limpiar</button>
                        </form>
                    </div>

                    <table class="card-table table">
                        <caption>Medicamentos en inventario</caption>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Forma farmaceutica</th>
                                <th scope="col">Status</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaMedicamentos">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</div>
</div>