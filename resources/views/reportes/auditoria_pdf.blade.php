<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Auditoria</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 20px; }
        .title { color: #0d6efd; margin: 0; }
        .section { margin-bottom: 15px; }
        .label { font-weight: bold; color: #495057; display: block; margin-bottom: 5px; }
        .content { background-color: #f8f9fa; padding: 10px; border-left: 4px solid #dee2e6; margin: 0; }
    </style>
</head>
<body>

    <h2>Reporte General de Auditoría</h2>
    <p>Generado el: {{ $fecha_emision }}</p>

    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Usuario</th>
                <th>Módulo</th>
                <th>Acción</th>
                <th>Descripción</th>
                <th>Fecha/Hora</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auditorias as $item)
                <tr>
                    <td>
                        {{ $item->usuario?->persona?->nombres }} 
                        {{ $item->usuario?->persona?->apellidos }}
                    </td>
                    <td>{{ $item->modulo }}</td>
                    <td>{{ $item->accion }}</td>
                    <td>{{ $item->descripcion }}</td>
                    <td>{{ $item->fecha_hora }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
