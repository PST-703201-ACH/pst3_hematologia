<div class="card card-primary card-outline">
	<div class="card-header">
		<h3 class="card-title">Agenda de citas</h3>
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
		        	<h4>DATOS DE PACIENTE</h4>
		        	<input type="hidden" id="usuCitaReg" name="usuCitaReg">
			        <div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombre1">Primer nombre del paciente</label>
					    <input type="text" class="form-control" name="pacienteNombre1" autocomplete="off">
					    <small class="text-danger" id="pacienteNombre1"></small>
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombre2">Segundo nombre del paciente</label>
					    <input type="text" class="form-control" name="pacienteNombre2" placeholder="(OPCIONAL)" autocomplete="off">
					    <small class="text-danger" id="pacienteNombre2"></small>
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellido1">Primer apellido del paciente</label>
					    <input type="text" class="form-control" name="pacienteApellido1" autocomplete="off">
					    <small class="text-danger" id="pacienteApellido1"></small>
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellido2">Segundo apellido del paciente</label>
					    <input type="text" class="form-control" name="pacienteApellido2" placeholder="(OPCIONAL)" autocomplete="off">
					    <small class="text-danger" id="pacienteApellido2"></small>
					  </div>
					</div>

					<div class="row">
						<div class="form-group col-md-6 mb-3">
							<label for="naciPaReg">Fecha de nacimiento</label>
							<input type="date" class="form-control" name="naciPaReg" autocomplete="off">
							<small class="text-danger" id="naciPaReg"></small>
						</div>
					</div>

					<h4>DATOS DE REPRESENTANTE</h4>
					<div class="row">
					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombre1">Primer nombre del representante</label>
					    <input type="text" class="form-control" name="represNombre1" autocomplete="off">
					    <small class="text-danger" id="represNombre1"></small>
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombre2">Segundo nombre del representante</label>
					    <input type="text" class="form-control" name="represNombre2" placeholder="(OPCIONAL)" autocomplete="off">
					    <small class="text-danger" id="represNombre2"></small>
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellido1">Primer apellido del representante</label>
					    <input type="text" class="form-control" name="represApellido1" autocomplete="off">
					    <small class="text-danger" id="represApellido1"></small>
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellido2">Segundo apellido del representante</label>
					    <input type="text" class="form-control" name="represApellido2" placeholder="(OPCIONAL)" autocomplete="off">
					    <small class="text-danger" id="represApellido2"></small>
					  </div>
					</div>

					<div class="row">
						<div class="col-md-8">
	                        <label for="cedula">Cédula de Identidad</label>
	                        <div class="row">
	                            <div class="form-group col-md-6 mb-3">
	                                <select name="nacPaReg" class="form-control">
	                                    <option value="" disabled selected>Seleccione una nacionalidad</option>
	                                    <option value="V">V (Venezolano)</option>
	                                    <option value="E">E (Extranjero)</option>
	                                </select>
	                                <small class="text-danger" id="nacPaReg"></small>
	                            </div>

	                            <div class="form-group col-md-6 mb-3">
	                                <input type="text" name="cedulaPaReg" class="form-control" placeholder="Ej: 25123456">
	                                <small class="text-danger" id="cedulaPaReg"></small>
	                            </div>
	                        </div>
	                    </div>
					</div>

					<h4>DATOS DE CITA</h4>

		          <div class="form-group">
		            <label for="hc">Nº de Historia Clinica</label>
		            <input type="text" onfocus="(this.type='number')" placeholder="Ingrese el Nº de historia clinica" onblur="(this.type='text')" class="form-control" name="hc" autocomplete="off">
		          </div>

		          <div class="form-group">
		            <label for="fechaCita">Fecha</label>
		            <input type="date" class="form-control" name="fechaCita" id="fechaCita" autocomplete="off" readonly>
		            <small class="text-danger" id="fechaCitaReg"></small>
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
		        	<h4>DATOS DE PACIENTE</h4>
		        	<input type="hidden" id="usuCitaRep" name="usuCitaRep">
			        <div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombreRep1">Primer nombre del paciente</label>
					    <input type="text" class="form-control" name="pacienteNombreRep1" id="pacienteNombreRep1" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteNombreRep2">Segundo nombre del paciente</label>
					    <input type="text" class="form-control" name="pacienteNombreRep2" id="pacienteNombreRep2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellidoRep1">Primer apellido del paciente</label>
					    <input type="text" class="form-control" name="pacienteApellidoRep1" id="pacienteApellidoRep1" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="pacienteApellidoRep2">Segundo apellido del paciente</label>
					    <input type="text" class="form-control" name="pacienteApellidoRep2" id="pacienteApellidoRep2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<h4>DATOS DE REPRESENTANTE</h4>
					<div class="row">
					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombreRep1">Primer nombre del representante</label>
					    <input type="text" class="form-control" name="represNombreRep1" id="represNombreRep1" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represNombreRep2">Segundo nombre del representante</label>
					    <input type="text" class="form-control" name="represNombreRep2" id="represNombreRep2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<div class="row">	
					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellidoRep1">Primer apellido del representante</label>
					    <input type="text" class="form-control" name="represApellidoRep1" id="represApellidoRep1" autocomplete="off">
					  </div>

					  <div class="form-group col-md-6 mb-3">
					    <label for="represApellidoRep2">Segundo apellido del representante</label>
					    <input type="text" class="form-control" name="represApellidoRep2" id="represApellidoRep2" placeholder="(OPCIONAL)" autocomplete="off">
					  </div>
					</div>

					<div class="row">
						<div class="col-md-8">
	                        <label for="cedula">Cédula de Identidad</label>
	                        <div class="row">
	                            <div class="form-group col-md-6 mb-3">
	                                <select name="nacPaRep" class="form-control">
	                                    <option value="" disabled selected>Seleccione una nacionalidad</option>
	                                    <option value="V">V (Venezolano)</option>
	                                    <option value="E">E (Extranjero)</option>
	                                </select>
	                                <small class="text-danger" id="nacPaRep"></small>
	                            </div>

	                            <div class="form-group col-md-6 mb-3">
	                                <input type="text" name="cedulaPaRep" class="form-control" placeholder="Ej: 25123456">
	                                <small class="text-danger" id="cedulaPaRep"></small>
	                            </div>
	                        </div>
	                    </div>
					</div>

					<h4>DATOS DE CITA</h4>

		          <div class="form-group">
		            <label for="hcRep">Nº de Historia Clinica</label>
		            <input type="text" onfocus="(this.type='number')" placeholder="Ingrese el Nº de historia clinica" onblur="(this.type='text')" class="form-control" name="hcRep" id="hcRep" autocomplete="off" readonly>
		          </div>

		          <div class="form-group">
		            <label for="fechaCitaRep">Fecha</label>
		            <input type="date" class="form-control" name="fechaCitaRep" id="fechaCitaRep" autocomplete="off">
		            <small class="text-danger" id="fechaCitaRepro"></small>
		          </div>

		          <div class="form-group">
		            <label for="fechaHoraRep">Hora</label>
		            <input type="time" class="form-control" name="fechaHoraRep" autocomplete="off">
		            <small class="text-danger" id="fechaHoraRep"></small>
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