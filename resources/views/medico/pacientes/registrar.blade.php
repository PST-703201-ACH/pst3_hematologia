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
                                <input type="text" name="hcPaConReg" id="hcPaConReg" class="form-control" placeholder="Ej: HC-0482" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <label for="cedula">Cédula de Identidad <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-3">
                                <select name="nacPaConReg" id="nacPaConReg" class="form-control" required>
                                    <option value="V">V (Venezolano)</option>
                                    <option value="E">E (Extranjero)</option>
                                </select>
                            </div>

                            <div class="col-md-9">
                                <input type="text" name="cedulaPaConReg" id="cedulaPaConReg" class="form-control" placeholder="Ej: 25123456">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Primer Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombrePa1ConReg" id="nombrePa1ConReg" class="form-control" placeholder="Ej: Juan">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Segundo Nombre</label>
                            <input type="text" name="nombrePa2ConReg" id="nombrePa2ConReg" class="form-control" placeholder="Ej: Carlos">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Primer Apellido <span class="text-danger">*</span></label>
                            <input type="text" name="apellidoPa1ConReg" id="apellidoPa1ConReg" class="form-control" placeholder="Ej: Pérez">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Segundo Apellido</label>
                            <input type="text" name="apellidoPa2ConReg" id="apellidoPa2ConReg" class="form-control" placeholder="Ej: Rodríguez">
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" name="naciPaConReg" id="naciPaConReg" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sexo <span class="text-danger">*</span></label>
                            <select name="sexoPaConReg" id="sexoPaConReg" class="form-control">
                                <option value="">Seleccione el genero</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Correo Electrónico</label>
                            <input type="email" name="emailPaConReg" id="emailPaConReg" class="form-control" placeholder="paciente@correo.com">
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
                                <input type="text" name="tlfPaConReg" id="tlfPaConReg" class="form-control" placeholder="Ej: 4121234567">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Dirección Exacta</label>
                            <textarea name="dirPaConReg" id="dirPaConReg" class="form-control" rows="2" placeholder="Calle, edificio, casa, piso..."></textarea>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 text-primary border-bottom pb-2"><i class="fas fa-map-marked-alt mr-1"></i> Ubicación Geográfica</h5>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estado <span class="text-danger">*</span></label>
                            <select name="estadoPaConReg" id="estadoPaConReg" class="form-control">
                                <option value="">Seleccione un estado</option>
                                
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Municipio</label>
                            <select name="muniPaConReg" id="muniPaConReg" class="form-control">
                                <option value="">Seleccione un municipio</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Parroquia</label>
                            <select name="parroquiaPaConReg" id="parroquiaPaConReg" class="form-control">
                                <option value="">Seleccione una parroquia</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




