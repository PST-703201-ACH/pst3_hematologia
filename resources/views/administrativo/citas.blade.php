<div class="card card-primary card-outline">
	<div class="card-header">
		<h3 class="card-title">Citas</h3>
	</div>

	<div class="card-body">

		<div class="d-flex align-items-center justify-content-between mb-3">

		    <div>
				<button id="btnRefrescarCi" class="btn btn-primary"><i class="fas fa-undo" aria-hidden="true"></i>
				</button>
		    </div>

		    <form onsubmit="event.preventDefault();" class="d-flex align-items-center m-0" id="formBusquedaCi">
		        <input 
		            type="text" 
		            name="busqueda_ci"
		            id="inputBuscar" 
		            placeholder="Buscar..." 
		            class="form-control me-2" 
		            style="width: 250px;"
		            autocomplete="off" 
		        >
		    </form>
		</div>

		<div class="table-responsive">
			<table class="card-table table">
				<caption>Citas de pacientes agendadas</caption>
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Nombre</th>
						<th scope="col">Representante</th>
						<th scope="col">Cedula</th>
						<th scope="col">Nºo de H.C</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody id="cuerpoTablaCitas">

				</tbody>
			</table>
		</div>
	</div>	
</div>