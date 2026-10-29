<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Prueba</title>
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

    <div class="header">
        <h2 class="title">Reporte Clínico de Prueba</h2>
        <small>Laravel 12 & DomPDF</small>
    </div>

    <div class="section">
        <span class="label">Antecedentes Personales:</span>
        <p class="content">{{ $antecedentes }}</p>
    </div>

    <div class="section">
        <span class="label">Plan de Trabajo:</span>
        <p class="content">{{ $plan }}</p>
    </div>

</body>
</html>
