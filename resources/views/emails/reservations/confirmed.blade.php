<x-mail::message>
# ¡Tu reserva ha sido confirmada!

Hola **{{ $reservation->user->name }}**,

Nos alegra informarte que tu reserva en **Nexus Coworking** ha sido confirmada exitosamente.

## Detalles de la Reserva:
- **Sala:** {{ $reservation->room->name }}
- **Fecha de Inicio:** {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y h:i A') }}
- **Fecha de Fin:** {{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y h:i A') }}
- **Costo Total:** ${{ number_format($reservation->total_price, 2) }}

Adjunto a este correo encontrarás el comprobante en formato PDF con las reglas del espacio y tu código de acceso.

Gracias por elegirnos,<br>
{{ config('app.name') }}
</x-mail::message>
