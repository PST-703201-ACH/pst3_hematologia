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
		        <h5 class="modal-title" id="eventModalLabel">Agendar cita</h5>
		        <button type="button" class="close" data-dismiss="modal">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form id="formReg" action="{{ route('cita.agendar') }}" method="POST">
		        	@csrf
		        	<h4 style="text-align: center;">DATOS DE PACIENTE</h4>
			        <div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombre1">Primer nombre del paciente</label>
					    <input type="text" class="form-control" name="pacienteNombre1" id="pacienteNombre1" placeholder="" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombre2">Segundo nombre del paciente</label>
					    <input type="text" class="form-control" name="pacienteNombre2" id="pacienteNombre2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellido1">Primer apellido del paciente</label>
					    <input type="text" class="form-control" name="pacienteApellido1" id="pacienteApellido1" placeholder="" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellido2">Segundo apellido del paciente</label>
					    <input type="text" class="form-control" name="pacienteApellido2" id="pacienteApellido2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<h4 style="text-align: center;">DATOS DE REPRESENTANTE</h4>
					<div class="row">
					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombre1">Primer nombre del representante</label>
					    <input type="text" class="form-control" name="represNombre1" id="represNombre1" placeholder="" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombre2">Segundo nombre del representante</label>
					    <input type="text" class="form-control" name="represNombre2" id="represNombre2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellido1">Primer apellido del representante</label>
					    <input type="text" class="form-control" name="represApellido1" id="represApellido1" placeholder="" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellido2">Segundo apellido del representante</label>
					    <input type="text" class="form-control" name="represApellido2" id="represApellido2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<h4 style="text-align: center;">DATOS DE CITA</h4>

		          <div class="form-group">
		            <label for="hc">Nº de Historia Clinica</label>
		            <input type="text" onfocus="(this.type='number')" placeholder="Ingrese el Nº de historia clinica" onblur="(this.type='text')" class="form-control" name="hc" id="hc" autocomplete="off">
		          </div>

		          <div class="form-group">
		            <label for="fechaCita">Fecha</label>
		            <input type="date" class="form-control" name="fechaCita" id="fechaCita" autocomplete="off" readonly>
		          </div>

		          <div class="form-group">
		            <label for="fechaHora">Hora</label>
		            <input type="time" class="form-control" name="fechaHora" id="fechaHora" autocomplete="off">
		          </div>
		          <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">
			          <i class="fas fa-times mr-1"></i> Cerrar
			        </button>
			        <button type="button" class="btn btn-danger" style="display:none">
			          <i class="fas fa-trash mr-1"></i> Eliminar
			        </button>
			        <button type="submit" class="btn btn-primary">
			          <i class="fas fa-save mr-1"></i> Guardar
			        </button>
			      </div>
			      <div id="alertaRegCita"></div>
		        </form>
		        <div class="modal fade" id="registrandoCita" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
				  <div class="modal-dialog modal-dialog-centered">
				    <div class="modal-content">
				      <div class="modal-body text-center p-4">
				        <div class="spinner-border text-primary" role="status"></div>
				        <h5 class="mt-3">Registrando cita...</h5>
				        <p class="text-muted">Por favor, no cierres la ventana.</p>
				      </div>
				    </div>
				  </div>
				</div>
		      </div>		
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modalReprogramar" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="eventModalLabel">Reprogramar cita</h5>
		        <button type="button" class="close" data-dismiss="modal">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form id="formRep" action="{{ route('cita.rep') }}" method="POST">
		        	@csrf
		        	<h4 style="text-align: center;">DATOS DE PACIENTE</h4>
			        <div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombreRep1">Primer nombre del paciente</label>
					    <input type="text" class="form-control" name="pacienteNombreRep1" id="pacienteNombreRep1" placeholder="" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombreRep2">Segundo nombre del paciente</label>
					    <input type="text" class="form-control" name="pacienteNombreRep2" id="pacienteNombreRep2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellidoRep1">Primer apellido del paciente</label>
					    <input type="text" class="form-control" name="pacienteApellidoRep1" id="pacienteApellidoRep1" placeholder="" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellidoRep2">Segundo apellido del paciente</label>
					    <input type="text" class="form-control" name="pacienteApellidoRep2" id="pacienteApellidoRep2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<h4 style="text-align: center;">DATOS DE REPRESENTANTE</h4>
					<div class="row">
					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombreRep1">Primer nombre del representante</label>
					    <input type="text" class="form-control" name="represNombreRep1" id="represNombreRep1" placeholder="" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombreRep2">Segundo nombre del representante</label>
					    <input type="text" class="form-control" name="represNombreRep2" id="represNombreRep2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellidoRep1">Primer apellido del representante</label>
					    <input type="text" class="form-control" name="represApellidoRep1" id="represApellidoRep1" placeholder="" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellidoRep2">Segundo apellido del representante</label>
					    <input type="text" class="form-control" name="represApellidoRep2" id="represApellidoRep2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<h4 style="text-align: center;">DATOS DE CITA</h4>

		          <div class="form-group">
		            <label for="hcRep">Nº de Historia Clinica</label>
		            <input type="text" onfocus="(this.type='number')" placeholder="Ingrese el Nº de historia clinica" onblur="(this.type='text')" class="form-control" name="hcRep" id="hcRep" autocomplete="off" readonly>
		          </div>

		          <div class="form-group">
		            <label for="fechaCitaRep">Fecha</label>
		            <input type="date" class="form-control" name="fechaCitaRep" id="fechaCitaRep" autocomplete="off">
		          </div>

		          <div class="form-group">
		            <label for="fechaHoraRep">Hora</label>
		            <input type="time" class="form-control" name="fechaHoraRep" id="fechaHoraRep" autocomplete="off">
		          </div>
		          <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">
			          <i class="fas fa-times mr-1"></i> Cerrar
			        </button>
			        <button type="button" class="btn btn-danger" style="display:none">
			          <i class="fas fa-trash mr-1"></i> Eliminar
			        </button>
			        <button type="submit" class="btn btn-primary">
			          <i class="fas fa-save mr-1"></i> Guardar
			        </button>
			      </div>
			      <div id="alertaRepCita"></div>
		        </form>
		        <div class="modal fade" id="reprogramandoCita" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
				  <div class="modal-dialog modal-dialog-centered">
				    <div class="modal-content">
				      <div class="modal-body text-center p-4">
				        <div class="spinner-border text-primary" role="status"></div>
				        <h5 class="mt-3">Reprogramando cita...</h5>
				        <p class="text-muted">Por favor, no cierres la ventana.</p>
				      </div>
				    </div>
				  </div>
				</div>
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