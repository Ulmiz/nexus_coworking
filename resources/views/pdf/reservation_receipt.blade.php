<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Reserva</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 28px; font-weight: bold; color: #4f46e5; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #666; }
        .content { margin-bottom: 40px; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .details-table th, .details-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .details-table th { background-color: #f8fafc; font-weight: bold; width: 40%; }
        .rules { background-color: #f0fdf4; padding: 20px; border-left: 5px solid #22c55e; margin-top: 30px; border-radius: 4px; }
        .rules h3 { color: #166534; margin-top: 0; }
        .footer { text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 20px; margin-top: 50px; }
        .total { font-size: 18px; font-weight: bold; color: #4f46e5; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Nexus Coworking</div>
        <div class="subtitle">Comprobante Oficial de Reserva #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="content">
        <h2>Hola, {{ $reservation->user->name }}</h2>
        <p>A continuación se presentan los detalles oficiales de tu reserva. Por favor presenta este documento digital al momento de ingresar.</p>

        <table class="details-table">
            <tr>
                <th>Sala Reservada</th>
                <td><strong>{{ $reservation->room->name }}</strong> (Capacidad: {{ $reservation->room->capacity }} personas)</td>
            </tr>
            <tr>
                <th>Fecha y Hora de Inicio</th>
                <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y - h:i A') }}</td>
            </tr>
            <tr>
                <th>Fecha y Hora de Fin</th>
                <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y - h:i A') }}</td>
            </tr>
            <tr>
                <th>Estado</th>
                <td><span style="color: #166534; font-weight:bold;">CONFIRMADA</span></td>
            </tr>
            <tr>
                <th>Costo Total Abonado</th>
                <td class="total">${{ number_format($reservation->total_price, 2) }} USD</td>
            </tr>
        </table>
    </div>

    <div class="rules">
        <h3>Reglas del Espacio</h3>
        <ul>
            <li>No se permite ingresar alimentos a las salas de juntas.</li>
            <li>Mantener un tono de voz moderado en pasillos.</li>
            <li>Entregar la sala en las mismas condiciones de limpieza.</li>
            <li><strong>Código de Acceso WiFi:</strong> NEXUS-{{ date('Y') }}</li>
        </ul>
    </div>

    <div class="footer">
        Este documento es generado automáticamente. Si tienes alguna duda, contacta a soporte@nexus.com.<br>
        Fecha de emisión: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
