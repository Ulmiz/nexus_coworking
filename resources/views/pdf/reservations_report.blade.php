<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Reservas</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 28px; font-weight: bold; color: #4f46e5; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 20px; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Nexus Coworking</div>
        <div class="subtitle">Reporte de Reservas - {{ $generatedAt->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Sala</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estado</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $reservation)
            <tr>
                <td>{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $reservation->user->name }}</td>
                <td>{{ $reservation->room->name }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y h:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y h:i A') }}</td>
                <td>{{ $reservation->getStatusLabel() }}</td>
                <td>${{ number_format($reservation->total_price, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;">No hay reservas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente por Nexus Coworking.
    </div>
</body>
</html>
