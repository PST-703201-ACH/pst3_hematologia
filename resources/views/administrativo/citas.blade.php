<div class="card card-primary card-outline">
	<div class="card-header">
		<h3 class="card-title">Calendario de agendamiento de cita</h3>
	</div>

	<div class="card-body">
		<div id="calendar" style="min-height: 500px;"></div>

		<div class="modal fade" id="modalCita" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="eventModalLabel">Agendar/Reprogramar cita</h5>
		        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form id="formCita">
		        	<h4 style="text-align: center;">DATOS DE PACIENTE</h4>
			        <div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombre1">Primer nombre del paciente</label>
					    <input type="text" class="form-control" id="pacienteNombre1" placeholder="Ingrese el primer nombre del paciente" required autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombre2">Segundo nombre del paciente</label>
					    <input type="text" class="form-control" id="pacienteNombre2" placeholder="Ingrese el segundo nombre del paciente" required autocomplete="off">
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellido1">Primer apellido del paciente</label>
					    <input type="text" class="form-control" id="pacienteApellido1" placeholder="Ingrese el primer apellido del paciente" required autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellido2">Segundo apellido del paciente</label>
					    <input type="text" class="form-control" id="pacienteApellido2" placeholder="Ingrese el segundo apellido del paciente" required autocomplete="off">
					  </div>
					</div>

					<h4 style="text-align: center;">DATOS DE REPRESENTANTE</h4>
					<div class="row">
					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombre1">Primer nombre del representante</label>
					    <input type="text" class="form-control" id="represNombre1" placeholder="Ingrese el primer nombre del representante" required autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombre2">Segundo nombre del representante</label>
					    <input type="text" class="form-control" id="represNombre2" placeholder="Ingrese el segundo nombre del representante" required autocomplete="off">
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellido1">Primer apellido del representante</label>
					    <input type="text" class="form-control" id="represApellido1" placeholder="Ingrese el primer apellido del representante" required autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellido2">Segundo apellido del representante</label>
					    <input type="text" class="form-control" id="represApellido2" placeholder="Ingrese el segundo apellido del representante" required autocomplete="off">
					  </div>
					</div>

					<h4 style="text-align: center;">DATOS DE CITA</h4>

		          <div class="form-group">
		            <label for="hc">Nº de Historia Clinica</label>
		            <input type="number" class="form-control" id="hc" placeholder="Ingrese el nº de historia clinica correspondiente al paciente" required autocomplete="off">
		          </div>

		          <div class="form-group">
		            <label for="fechaCita">Fecha</label>
		            <input type="date" class="form-control" id="fechaCita" required autocomplete="off" readonly>
		          </div>

		          <div class="form-group">
		            <label for="eventTime">Hora</label>
		            <input type="time" class="form-control" id="eventTime" required autocomplete="off">
		          </div>
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
		          <i class="fas fa-times mr-1"></i> Cerrar
		        </button>
		        <button type="button" id="deleteEvent" class="btn btn-danger" style="display:none">
		          <i class="fas fa-trash mr-1"></i> Eliminar
		        </button>
		        <button type="button" id="saveEvent" class="btn btn-primary">
		          <i class="fas fa-save mr-1"></i> Guardar
		        </button>
		      </div>				
		    </div>
		  </div>
		</div>
	</div>
</div>


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
						<th scope="col">Nº de H.C</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody id="cuerpoTablaCitas">

				</tbody>
			</table>
		</div>



		

	</div>	
</div>