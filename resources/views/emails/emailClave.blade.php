<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Estilos básicos para que se vea bien en móviles */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4Link; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0; }
        .header { background-color: #28a745; color: #ffffff; padding: 20px; text-align: center; }
        .content { padding: 30px; line-height: 1.6; color: #333333; }
        .footer { background-color: #f8f9fa; color: #777777; padding: 15px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #28a745; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Se ha restablecido tu contraseña en el sistema del servicio de Hematologia Dr. Walles Camarillo</h1>
        </div>
        <div class="content">
            <p>Tu restablecimiento de contraseña en el sistema del servicio de hematología se ha completado con éxito. A partir de ahora puedes acceder a tu panel de control utilizando tus credenciales.</p>
            
            <div style="background-color: #f1f1f1; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 5px 0;"><strong>Usuario:</strong> {{ $datos['cedula'] }}</p>
                <p style="margin: 5px 0;"><strong>Contraseña:</strong> <em>{{ $datos['clave'] }}</em></p>
            </div>

        </div>
        
        <div class="footer">
            &copy; {{ date('d/m/Y') }} Sistema de Hematología PST3. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
