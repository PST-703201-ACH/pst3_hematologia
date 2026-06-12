<div class="card card-primary" style="width: 90%; max-width: 60rem; margin: auto;">
	<div class="card-header">Actualizacion de usuario</div>
	<div class="card-body">
		<form id="formUp" action="{{ route('persona.actualizar') }}" method="POST">
			@csrf
			<div id="alertUpdate"></div>
			<div class="row">
				<div class="col">
					<label>Primer nombre</label>
					<input type="text" class="form-control" name="nombre1" id="actualizar0" placeholder="Ingrese el primer nombre del nuevo usuario" autocomplete="off">
				</div>
				<div class="col">
					<label>Segundo nombre</label>
					<input type="text" class="form-control" name="nombre2" id="actualizar0.5" placeholder="Ingrese el segundo nombre del nuevo usuario" autocomplete="off">
				</div>				
			</div>
			<div class="row">
				<div class="col">
					<label>Primer apellido</label>
					<input type="text" class="form-control" name="apellido1" id="actualizar1" placeholder="Ingrese el primer apellido del nuevo usuario" autocomplete="off">
				</div>
				<div class="col">
					<label>Segundo apellido</label>
					<input type="text" class="form-control" name="apellido2" id="actualizar1.5" placeholder="Ingrese el segundo apellido del nuevo usuario" autocomplete="off">
				</div>	
			</div>
			
			<div class="form-group">
				<label>Fecha de nacimiento</label>
				<input type="text" onfocus="(this.type='date')" placeholder="Ingrese la fecha de nacimiento del nuevo usuario" onblur="(this.type='text')" class="form-control" name="fecha_nac" id="actualizar2" autocomplete="off">
			</div>
			<div class="form-group">
				<label>Sexo</label>
				<select class="form-control" name="sexo" id="actualizar3">
					<option disabled selected>Seleccione el genero del nuevo usuario</option>
					<option value="Masculino">Masculino</option>
					<option value="Femenino">Femenino</option>
				</select>
			</div>
			<label>Telefono</label>
			<div class="input-group mb-3">
		        <div class="input-group-prepend">
		          <span class="input-group-text">0-</span>
		        </div>
					<input type="text" class="form-control" name="telefono" id="actualizar5" placeholder="Ingrese el numero telefonico del nuevo usuario (sin 0 al inicio)" autocomplete="off">
			</div>
			<div class="form-group">
				<label>Correo electronico</label>
				<input type="email" class="form-control" name="correo" id="actualizar6" placeholder="Ingrese la direccion de correo electronico del nuevo usuario" autocomplete="off">
				<small id=6.5 class="form-text text-muted">Se enviara una nueva contraseña de acceso a este correo</small>
			</div>
			<div class="form-group">
				<label>Estado</label>
				<select class="form-control" name="estado" id="actualizar7">
					<option disabled selected>Seleccione el estado donde vive el nuevo usuario</option>
				</select>
			</div>
			<div class="form-group">
				<label>Municipio</label>
				<select class="form-control" name="municipio" id="actualizar8">
					<option disabled selected>Seleccione el municipio donde vive el nuevo usuario</option>
				</select>
			</div>
			<div class="form-group">
				<label>Parroquia</label>
				<select class="form-control" name="parroquia" id="actualizar9">
					<option disabled selected>Seleccione la parroquia donde vive el nuevo usuario</option>
				</select>
			</div>
			<div class="form-group">
				<label>Direccion de domicilio exacta</label>
				<input type="text" class="form-control" name="direccion" id="actualizar10" placeholder="Ingrese la direccion exacta donde vive el nuevo usuario" autocomplete="off">
			</div>
			<div class="form-group">
				<label>Rol</label>
				<select class="form-control" name="rol" id="actualizar11">
					<option value="" disabled selected>Seleccione el rol del nuevo usuario</option>
				</select>
			</div>
			<button type="submit" class="btn btn-success">Actualizar</button>
			<button type="button" class="btn btn-danger" id="btnCancelarUp">Salir</button>
			<input type="hidden" name="tipo_up" value="usuario" autocomplete="off">
			<input type="hidden" name="persona" id="X" autocomplete="off">
		</form>
		<div class="modal fade" id="actualizandoModal" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
		  <div class="modal-dialog modal-dialog-centered">
		    <div class="modal-content">
		      <div class="modal-body text-center p-4">
		        <div class="spinner-border text-primary" role="status"></div>
		        <h5 class="mt-3">Actualizando usuario...</h5>
		        <p class="text-muted">Por favor, no cierres la ventana.</p>
		      </div>
		    </div>
		  </div>
		</div>
	</div>
</div>

