<div class="card card-success" style="width: 90%; max-width: 60rem; margin: auto;">
	<div class="card-header">Registro de usuario</div>
	<div class="card-body">
		<form id="formReg" action="{{ route('persona.registrar') }}" method="POST">
			@csrf
			<div id="alertCreate"></div>
			<div class="row">
				<div class="col">
					<label>Primer nombre</label>
					<input type="text" class="form-control" name="nombre1" id="0" placeholder="Ingrese el primer nombre del nuevo usuario" autocomplete="off">
				</div>
				<div class="col">
					<label>Segundo nombre</label>
					<input type="text" class="form-control" name="nombre2" id="0.5" placeholder="Ingrese el segundo nombre del nuevo usuario" autocomplete="off">
				</div>				
			</div>
			<div class="row">
				<div class="col">
					<label>Primer apellido</label>
					<input type="text" class="form-control" name="apellido1" id="1" placeholder="Ingrese el primer apellido del nuevo usuario" autocomplete="off">
				</div>
				<div class="col">
					<label>Segundo apellido</label>
					<input type="text" class="form-control" name="apellido2" id="1.5" placeholder="Ingrese el segundo apellido del nuevo usuario" autocomplete="off">
				</div>	
			</div>
			
			<div class="form-group">
				<label>Fecha de nacimiento</label>
				<input type="text" onfocus="(this.type='date')" placeholder="Ingrese la fecha de nacimiento del nuevo usuario" onblur="(this.type='text')" class="form-control" name="fecha_nac" id="2" autocomplete="off">
			</div>
			<div class="form-group">
				<label>Sexo</label>
				<select class="form-control" name="sexo" id="3">
					<option disabled selected>Seleccione el genero del nuevo usuario</option>
					<option value="Masculino">Masculino</option>
					<option value="Femenino">Femenino</option>
				</select>
			</div>
			<label>Cedula</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
		          	<select class="form-control input-group-text" name="nacionalidad" id="4">
		          		<option value="V">V</option>
		          		<option value="E">E</option>
		          	</select>
		        </div>

				<input type="text" class="form-control" name="cedula" id="4.5" placeholder="Ingrese el numero de cedula de identidad del nuevo usuario" autocomplete="off">
			</div>
			<label>Telefono</label>
			<div class="input-group mb-3">
		        <div class="input-group-prepend">
		          <span class="input-group-text">0-</span>
		        </div>
					<input type="text" class="form-control" name="telefono" id="5" placeholder="Ingrese el numero telefonico del nuevo usuario (sin 0 al inicio)" autocomplete="off">
			</div>
			<div class="form-group">
				<label>Correo electronico</label>
				<input type="email" class="form-control" name="correo" id="6" placeholder="Ingrese la direccion de correo electronico del nuevo usuario" autocomplete="off">
				<small id=6.5 class="form-text text-muted">La contraseña de acceso sera enviada a este correo</small>
			</div>
			<div class="form-group">
				<label>Estado</label>
				<select class="form-control" name="estado" id="7">
					<option disabled selected>Seleccione el estado donde vive el nuevo usuario</option>
				</select>
			</div>
			<div class="form-group">
				<label>Municipio</label>
				<select class="form-control" name="municipio" id="8">
					<option disabled selected>Seleccione el municipio donde vive el nuevo usuario</option>
				</select>
			</div>
			<div class="form-group">
				<label>Parroquia</label>
				<select class="form-control" name="parroquia" id="9">
					<option disabled selected>Seleccione la parroquia donde vive el nuevo usuario</option>
				</select>
			</div>
			<div class="form-group">
				<label>Direccion de domicilio exacta</label>
				<input type="text" class="form-control" name="direccion" id="10" placeholder="Ingrese la direccion exacta donde vive el nuevo usuario" autocomplete="off">
			</div>
			<div class="form-group">
				<label>Rol</label>
				<select class="form-control" name="rol" id="11">
					<option value="" disabled selected>Seleccione el rol del nuevo usuario</option>
				</select>
			</div>
			<button type="submit" class="btn btn-success">Registrar</button>
			<button type="button" class="btn btn-danger" id="btnCancelarReg">Salir</button>
			<input type="hidden" name="tipo_reg" value="usuario" autocomplete="off">
		</form>

		<div class="modal fade" id="registrandoModal" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
		  <div class="modal-dialog modal-dialog-centered">
		    <div class="modal-content">
		      <div class="modal-body text-center p-4">
		        <div class="spinner-border text-primary" role="status"></div>
		        <h5 class="mt-3">Registrando usuario...</h5>
		        <p class="text-muted">Por favor, no cierres la ventana.</p>
		      </div>
		    </div>
		  </div>
		</div>
	</div>
</div>