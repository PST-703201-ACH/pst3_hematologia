<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de citas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #343a40; font-size: 10px; }
        h1 { color: #007bff; font-size: 20px; margin-bottom: 4px; }
        .fecha { color: #6c757d; margin: 0 0 16px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #007bff; color: #fff; text-align: left; }
        th, td { border: 1px solid #dee2e6; padding: 7px; }
        tr:nth-child(even) { background: #f4f6f9; }
        .vacio { text-align: center; color: #6c757d; }
    </style>
</head>
<body>
    <h1>Reporte de citas registradas</h1>
    <p class="fecha">Generado el: {{ $fecha_emision }}</p>

    <table>
        <thead>
            <tr>
                <th>Fecha y hora (cita)</th>
                <th>Número de historia clínica</th>
                <th>Nombre del paciente</th>
                <th>Nombre del representante</th>
                <th>Consulta asociada</th>
                <th>Citas registradas en la fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($citas as $cita)
                <tr>
                    <td>{{ $cita['fecha_hora'] }}</td>
                    <td>{{ $cita['numero_hc'] }}</td>
                    <td>{{ $cita['paciente'] }}</td>
                    <td>{{ $cita['representante'] }}</td>
                    <td>{{ $cita['consulta'] }}</td>
                    <td>{{ $cita['conteo_fecha'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="vacio">No hay citas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
