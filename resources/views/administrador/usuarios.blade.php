<div class="card card-primary card-outline">
	<div class="card-header">
		<h3 class="card-title">Usuarios</h3>
	</div>

	<div class="card-body">

		<div class="d-flex align-items-center justify-content-between mb-3">

		    <div>
				<button id="btnRegistrarUsu" class="btn btn-success">Nuevo</button>
				<button id="btnRefrescarUsu" onclick="listarUsuarios()" class="btn btn-primary"><i class="fas fa-undo" aria-hidden="true"></i>
				</button>
		    </div>

		    <form onsubmit="event.preventDefault();" class="d-flex align-items-center m-0" id="formBusquedaUsu">
		        <input 
		            type="text" 
		            name="busqueda_usu"
		            id="inputBuscar" 
		            placeholder="Buscar..." 
		            class="form-control me-2" 
		            style="width: 250px;"
		            autocomplete="off" 
		        >
		        <select name="filtroRol_usu" class="form-control">
		        	<option value="">Todos los roles</option>
		        	<option value="1">Gerente</option>
		        	<option value="2">Administrativo</option>
		        	<option value="3">Medico</option>
		        	<option value="4">Enfermero</option>
		        </select>
		        <select name="filtroStatus_usu" class="form-control">
		        	<option value="">Todos los status</option>
		        	<option value="1">Activos</option>
		        	<option value="0">Inactivos</option>
		        </select>
		        <button type="button" id="btnLimpiar_usu" class="btn btn-danger d-none">Limpiar</button>
		    </form>
		</div>

		<div class="table-responsive">
			<table class="card-table table">
				<caption>Usuarios del sistema</caption>
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Nombre</th>
						<th scope="col">Rol</th>
						<th scope="col">Cedula</th>
						<th scope="col">Telefono</th>
						<th scope="col">Status</th>
						<th style="text-align: center;">Acciones</th>
					</tr>
				</thead>
				<tbody id="cuerpoTablaUsuarios">

				</tbody>
			</table>
		</div>
	</div>	
</div>