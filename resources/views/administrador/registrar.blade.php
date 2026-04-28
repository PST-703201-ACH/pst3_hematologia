<div class="card card-success">
	<div class="card-header">Registro de usuario</div>
	<div class="card-body">
		<form id="formUsu" action="{{ route('persona.store') }}" method="POST">
			@csrf
			<div id="alertaUsuario"></div>
			<div class="row">
				<div class="col">
					<label for="0">Primer nombre</label>
					<input type="text" class="form-control" name="nombre1" id="0" placeholder="Inserte el primer nombre del nuevo usuario" autocomplete="off">
				</div>
				<div class="col">
					<label for="0.5">Segundo nombre</label>
					<input type="text" class="form-control" name="nombre2" id="0.5" placeholder="Inserte el segundo nombre del nuevo usuario" autocomplete="off">
				</div>				
			</div>
			<div class="row">
				<div class="col">
					<label for="1">Primer apellido</label>
					<input type="text" class="form-control" name="apellido1" id="1" placeholder="Inserte el primer apellido del nuevo usuario" autocomplete="off">
				</div>
				<div class="col">
					<label for="1.5">Segundo apellido</label>
					<input type="text" class="form-control" name="apellido2" id="1.5" placeholder="Inserte el segundo apellido del nuevo usuario" autocomplete="off">
				</div>	
			</div>
			
			<div class="form-group">
				<label for="2">Fecha de nacimiento</label>
				<input type="date" class="form-control" name="fecha_nac" id="2" autocomplete="off">
			</div>
			<div class="form-group">
				<label for="3">Sexo</label>
				<select class="form-control" name="sexo" id="3">
					<option disabled selected>Seleccione el genero del nuevo usuario</option>
					<option value="Masculino">Masculino</option>
					<option value="Femenino">Femenino</option>
				</select>
			</div>
			<div class="form-group">
				<label for="4">Cedula</label>
				<input type="text" class="form-control" name="cedula" id="4" placeholder="Inserte el numero de cedula de identidad del nuevo usuario" autocomplete="off">
			</div>
			<div class="form-group">
				<label for="5">Telefono</label>
				<input type="text" class="form-control" name="telefono" id="5" placeholder="Inserte el numero telefonico del nuevo usuario" autocomplete="off">
			</div>
			<div class="form-group">
				<label for="6">Correo electronico</label>
				<input type="text" class="form-control" name="correo" id="6" placeholder="Inserte la direccion de correo electronico del nuevo usuario" autocomplete="off">
				<small id=6.5 class="form-text text-muted">La contraseña de acceso sera enviada a este correo
			</div>
			<div class="form-group">
				<label for="7">Estado</label>
				<select class="form-control" name="estado" id="7">
					<option disabled selected>Seleccione el estado donde vive el nuevo usuario</option>
				</select>
			</div>
			<div class="form-group">
				<label for="8">Municipio</label>
				<select class="form-control" name="municipio" id="8">
					<option disabled selected>Seleccione el municipio donde vive el nuevo usuario</option>
				</select>
			</div>
			<div class="form-group">
				<label for="9">Parroquia</label>
				<select class="form-control" name="parroquia" id="9">
					<option disabled selected>Seleccione la parroquia donde vive el nuevo usuario</option>
				</select>
			</div>
			<div class="form-group">
				<label for="10">Direccion de domicilio exacta</label>
				<input type="text" class="form-control" name="direccion" id="10" placeholder="Inserte la direccion exacta donde vive el nuevo usuario" autocomplete="off">
			</div>
			<div class="form-group">
				<label for="11">Rol</label>
				<select class="form-control" name="rol" id="11">
					<option value="" disabled selected>Seleccione el rol del nuevo usuario</option>
				</select>
			</div>
			<button type="submit" class="btn btn-success">Registrar</button>
			<button type="button" class="btn btn-danger" id="btnCancelarReg">Cancelar</button>
			<input type="hidden" name="tipo_reg" value="usuario" autocomplete="off">
		</form>
	</div>
</div>

