<div class="card card-primary card-outline">
	<div class="card-header">
		<h3 class="card-title">Usuarios</h3>
	</div>

	<div class="card-body">
		<button id="btnRegistrarUsu" class="btn btn-success">Nuevo</button>
		<button id="btnRefrescarUsu" onclick="listarUsuarios()" class="btn btn-primary"><i class="fas fa-undo" aria-hidden="true"></i>
</button>
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