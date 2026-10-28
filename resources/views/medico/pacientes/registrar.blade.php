<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Datos de Paciente</h3>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="hc">N° Historia Clínica <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-folder-open"></i></span>
                                </div>
                                <input type="text" name="hcPaConReg" class="form-control" placeholder="Ej: HC-0482" readonly>
                            </div>
                            <small class="text-danger" id="hcPaConReg"></small>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <label for="cedula">Cédula de Identidad <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-4">
                                <select name="nacPaConReg" class="form-control">
                                    <option value="" disabled selected>Seleccione una nacionalidad</option>
                                    <option value="V">V (Venezolano)</option>
                                    <option value="E">E (Extranjero)</option>
                                </select>
                                <small class="text-danger" id="nacPaConReg"></small>
                            </div>

                            <div class="col-md-4">
                                <input type="text" name="cedulaPaConReg" class="form-control" placeholder="Ej: 25123456">
                                <small class="text-danger" id="cedulaPaConReg"></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Primer Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombrePa1ConReg" class="form-control" placeholder="Ej: Juan">
                            <small class="text-danger" id="nombrePa1ConReg"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Segundo Nombre</label>
                            <input type="text" name="nombrePa2ConReg" class="form-control" placeholder="Ej: Carlos">
                            <small class="text-danger" id="nombrePa2ConReg"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Primer Apellido <span class="text-danger">*</span></label>
                            <input type="text" name="apellidoPa1ConReg" class="form-control" placeholder="Ej: Pérez">
                            <small class="text-danger" id="apellidoPa1ConReg"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Segundo Apellido</label>
                            <input type="text" name="apellidoPa2ConReg" class="form-control" placeholder="Ej: Rodríguez">
                            <small class="text-danger" id="apellidoPa2ConReg"></small>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" name="naciPaConReg" class="form-control">
                            <small class="text-danger" id="naciPaConReg"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sexo <span class="text-danger">*</span></label>
                            <select name="sexoPaConReg" class="form-control">
                                <option value="">Seleccione el genero</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                            <small class="text-danger" id="sexoPaConReg"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Correo Electrónico</label>
                            <input type="email" name="emailPaConReg" class="form-control" placeholder="paciente@correo.com">
                            <small class="text-danger" id="emailPaConReg"></small>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Teléfono <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">0</span>
                                </div>
                                <input type="text" name="tlfPaConReg" class="form-control" placeholder="Ej: 4121234567">
                            </div>
                            <small class="text-danger" id="tlfPaConReg"></small>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Dirección Exacta</label>
                            <textarea name="dirPaConReg" class="form-control" rows="2" placeholder="Calle, edificio, casa, piso..."></textarea>
                            <small class="text-danger" id="dirPaConReg"></small>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 text-primary border-bottom pb-2"><i class="fas fa-map-marked-alt mr-1"></i> Ubicación Geográfica</h5>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estado <span class="text-danger">*</span></label>
                            <select name="estadoPaConReg" class="form-control">
                                <option value="" disabled selected>Seleccione un estado</option>
                                
                            </select>
                            <small class="text-danger" id="estadoPaConReg"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Municipio</label>
                            <select name="muniPaConReg" class="form-control">
                                <option value="" disabled selected>Seleccione un municipio</option>
                            </select>
                            <small class="text-danger" id="muniPaConReg"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Parroquia</label>
                            <select name="parroquiaPaConReg" class="form-control">
                                <option value="" disabled selected>Seleccione una parroquia</option>
                            </select>
                            <small class="text-danger" id="parroquiaPaConReg"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




