<h2>Registro de Nuevo Paciente</h2>

<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Datos Personales</h3>
            </div>
            
            <form action="{{ route('medico.pacientes.store') }}" id="formReg" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="reg_hc">N° Historia Clínica</label>
                                <input type="text" name="reg_hc" id="reg_hc" class="form-control" placeholder="Ej: 0001" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="reg_cedula">Cédula de Identidad</label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group-prepend">
                                    <select class="form-group input-group-text" name="reg_nac" id="reg_nac">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                    </select>
                                </div>

                                <input type="text" name="reg_cedula" id="reg_cedula" class="form-control" placeholder="Ej: 12345678" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_nombres">Primer nombre</label>
                                <input type="text" name="reg_nombre1" id="reg_nombre1" class="form-control" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_nombre2">Segundo nombre</label>
                                <input type="text" name="reg_nombre2" id="reg_nombre2" class="form-control" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_apellidos">Primer apellido</label>
                                <input type="text" name="reg_apellido1" id="reg_apellido1" class="form-control" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_apellido2">Segundo apellido</label>
                                <input type="text" name="reg_apellido2" id="reg_apellido2" class="form-control" required autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_fecha_nac">Fecha de Nacimiento</label>
                                <input onfocus="(this.type='date')" type="text" onblur="(this.type='text')" name="reg_fecha_nac" id="reg_fecha_nac" class="form-control" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_sexo">Sexo</label>
                                <select name="reg_sexo" id="reg_sexo" class="form-control" required autocomplete="off">
                                    <option value="">Seleccione...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_email">Correo Electrónico</label>
                                <input type="email" name="reg_email" id="reg_email" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_telefono">Teléfono</label>
                                <input type="text" name="reg_telefono" id="reg_telefono" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="reg_direccion">Dirección Exacta</label>
                                <textarea name="reg_direccion" id="reg_direccion" class="form-control" rows="2" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>Ubicación Geográfica</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_estado">Estado</label>
                                <select name="reg_estado" id="reg_estado" class="form-control">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_municipio">Municipio</label>
                                <select name="reg_municipio" id="reg_municipio" class="form-control">
                                    <option value="">Seleccione estado primero...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_parroquia">Parroquia</label>
                                <select name="reg_parroquia" id="reg_parroquia" class="form-control">
                                    <option value="">Seleccione municipio primero...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="button" id="btnCancelarReg" class="btn btn-danger">Salir</button>
                    <button type="reset" class="btn btn-secondary">Limpiar</button>
                    <button type="submit" class="btn btn-primary">Registrar Paciente</button>
                </div>
                <div id="alertCreate"></div>
            </form>

        <div class="modal fade" id="registrandoModal" data-backdrop="static" data-bs-backdrop="static" data-keyboard="false" data-bs-keyboard="false" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary" role="status"></div>
                <h5 class="mt-3">Registrando paciente...</h5>
                <p class="text-muted">Por favor, no cierres la ventana.</p>
              </div>
            </div>
          </div>
        </div>

        </div>
    </div>
</div>