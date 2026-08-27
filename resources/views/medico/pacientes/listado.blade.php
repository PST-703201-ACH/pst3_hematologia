    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Pacientes en Sistema</h3>
            <div class="card-tools">
                <a href="/medico" onclick="event.preventDefault();" id="btnRegistrar" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Paciente
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>N° Historia</th>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Sexo</th>
                        <th>Status</th>
                        <th style="width: 250px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaPacientes">
                        
                </tbody>
            </table>
        </div>
    </div>