<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hemos recibido tu mensaje - ServiPro</title>
    
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
            <div class="logo">Servi<span class="highlight">Pro</span></div>
            <h1>¡Gracias por contactarnos!</h1>
        </div>

        <div class="content">
            <p>Hola {{ $userData['name'] }},</p>

            <p>Hemos recibido tu mensaje correctamente. Nuestro equipo lo revisará y te responderá en breve.</p>

            <div class="message-summary">
                <h3>Resumen de tu mensaje:</h3>
                <p><strong>Asunto:</strong> {{ ucfirst($userData['subject']) }}</p>
                <p><strong>Mensaje:</strong> {{ Str::limit($userData['message'], 100) }}</p>
            </div>

            <p>Te responderemos dentro de las próximas 24 horas hábiles.</p>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
            <hr>
            <p>ServiPro - Tu plataforma de servicios profesionales</p>
        </div>
    </div>
</body>

</html>
