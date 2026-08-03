<div class="card card-primary card-outline">
	<div class="card-header">
		<h3 class="card-title">Auditoria</h3>
	</div>

	<div class="card-body">

		<div class="d-flex align-items-center justify-content-between mb-3">

		    <div>
				<button id="btnRefrescarAud" class="btn btn-primary" onclick="listarAuditoria()"><i class="fas fa-undo" aria-hidden="true"></i>
				</button>
		    </div>

		    <form onsubmit="event.preventDefault();" class="d-flex align-items-center m-0" id="formBusquedaAud">
		        <input 
		            type="text" 
		            name="busqueda_aud"
		            id="inputBuscar" 
		            placeholder="Buscar..." 
		            class="form-control me-2" 
		            style="width: 250px;"
		            autocomplete="off" 
		        >
		        <select name="filtroMod_aud" class="form-control">
		        	<option value="" disabled selected>Seleccione un modulo</option>
		        	<option value="Informacion medica">Informacion Medica</option>
		        	<option value="Atencion clinica">Atencion clinica</option>
		        	<option value="Protocolos">Protocolos</option>
		        	<option value="Laboratorios">Laboratorios</option>
		        	<option value="Usuarios">Usuarios</option>
		        </select>
		        <select name="filtroAcc_aud" class="form-control">
		        	<option value="" disabled selected>Seleccione una accion</option>
		        </select>
		        <button type="button" id="btnLimpiar_aud" class="btn btn-danger d-none">Limpiar</button>
		    </form>
		</div>

		<div class="table-responsive">
			<table class="card-table table">
				<caption>Informacion auditada</caption>
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Fecha</th>
						<th scope="col">Usuario</th>
						<th scope="col">Modulo</th>
						<th scope="col">Accion</th>
						<th scope="col">Descripcion</th>
					</tr>
				</thead>
				<tbody id="cuerpoTablaAuditoria">

				</tbody>
			</table>
		</div>
	</div>	
</div>