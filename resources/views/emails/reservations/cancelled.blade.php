<x-mail::message>
# Reserva Cancelada

Hola **{{ $reservation->user->name }}**,

Tu reserva en **{{ $reservation->room->name }}** ha sido cancelada.

<x-mail::panel>
### Detalles de la reserva cancelada
- **Sala:** {{ $reservation->room->name }}
- **Fecha:** {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y') }}
- **Horario:** {{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}
- **Estado:** Cancelada
</x-mail::panel>

Si tienes alguna duda, contáctanos a soporte@nexus.com.

<x-mail::button :url="route('reservations.index')">
Ver mis reservas
</x-mail::button>

Gracias,<br>
Nexus Coworking
</x-mail::message>
