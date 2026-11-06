<div class="card card-primary card-outline">
	<div class="card-header">
		<h3 class="card-title">Auditoria</h3>
	</div>

	<div class="card-body">

		<div class="d-flex align-items-center justify-content-between mb-3">

		    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
		    	<div class="d-flex gap-2">
					<button class="btn btn-primary" onclick="listarAuditoria()"><i class="fas fa-undo" aria-hidden="true"></i>
					</button>

					<button onclick="audiPdf()" class="btn btn-danger">
					    <i class="fas fa-file-pdf"></i>
					</button>

					{{-- El formulario POST conserva la sesión y protege la solicitud con el token CSRF. --}}
					<form method="POST" action="{{ route('admin.backup.database') }}" class="d-inline">
						@csrf
						<button type="submit" class="btn btn-info" title="Descargar respaldo de la base de datos" aria-label="Descargar respaldo de la base de datos">
							<i class="fas fa-database" aria-hidden="true"></i>
						</button>
					</form>
				</div>

				<div id="btnPagAud" class="d-flex gap-1"></div>
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
		        <input
		            type="date"
		            name="busqueda_fecha"
		            id="inputFecha" 
		            placeholder="Seleccione una fecha" 
		            class="form-control me-2"
		            autocomplete="off" 
		        >
		        <select name="filtroMod_aud" class="form-control">
		        	<option value="" disabled selected>Seleccione un modulo</option>

		        	<option value="Usuarios">Usuarios</option>
		        	<option value="Catalogo Medico">Catalogo Medico</option>
		        	<option value="Auditoria">Auditoria</option>
		        	
		        </select>
		        <select name="filtroAcc_aud" class="form-control">
		        	<option value="" disabled selected>Seleccione una accion</option>
		        	<option value="Registro">Registro</option>
		        	<option value="Listado">Listado</option>
		        	<option value="Actualizacion">Actualizacion</option>
		        	<option value="Exportacion">Exportacion</option>
		        	<option value="Habilitacion">Habilitacion</option>
		        	<option value="Inhabilitacion">Inhabilitacion</option>
		        	<option value="Consulta">Consulta</option>
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