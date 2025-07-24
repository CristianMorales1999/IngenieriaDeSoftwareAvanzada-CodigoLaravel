<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto - ServiPro</title>
    
    <!-- ... estilos ... -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
        }
        .message-box {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            color: #6b7280;
            font-size: 0.875rem;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #4b5563;
        }
        .timestamp {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 15px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .highlight {
            color: #fbbf24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                Servi<span class="highlight">Pro</span>
            </div>
            <h1 style="margin: 0;">Nuevo Mensaje de Contacto</h1>
        </div>

        <div class="content">
            <p>Se ha recibido un nuevo mensaje a través del formulario de contacto:</p>

            <div class="info-item">
                <span class="label">De:</span>
                <span>{{ $emailData['name'] }} ({{ $emailData['email'] }})</span>
            </div>

            <div class="info-item">
                <span class="label">Asunto:</span>
                <span>{{ ucfirst($emailData['subject']) }}</span>
            </div>

            <div class="message-box">
                <span class="label">Mensaje:</span>
                <p style="margin-top: 10px;">{{ $emailData['message'] }}</p>
            </div>

            <div class="timestamp">
                Enviado el {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="footer">
            <p>
                ServiPro - Tu plataforma de servicios profesionales<br>
                © {{ date('Y') }} Todos los derechos reservados
            </p>
        </div>
    </div>
</body>
</html>