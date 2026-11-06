    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Pacientes en Sistema</h3>
            <div class="card-tools">

            </div>
        </div>
        <div class="card-body">
            <table id="tabla-pacientes" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>N° Historia</th>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Sexo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($pacientes as $paciente)
                        <tr>
                            <td>{{ $paciente->hc }}</td>
                            <td>{{ $paciente->persona->cedula }}</td>
                            <td>{{ $paciente->persona->nombres }} {{ $paciente->persona->apellidos }}</td>
                            <td>{{ $paciente->persona->sexo }}</td>
                            <td>
                                <span class="badge {{ (int) $paciente->status === 1 ? 'badge-success' : 'badge-danger' }}">
                                    {{ (int) $paciente->status === 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabla-pacientes').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                },
                responsive: true,
                autoWidth: false
            });
        });
    </script>