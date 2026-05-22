<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationCancelled extends Notification
{
    use Queueable;

    public Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Reserva Cancelada',
            'message' => "Tu reserva en {$this->reservation->room->name} ha sido cancelada.",
            'reservation_id' => $this->reservation->id,
            'room' => $this->reservation->room->name,
            'start_time' => $this->reservation->start_time->toDateTimeString(),
            'end_time' => $this->reservation->end_time->toDateTimeString(),
            'type' => 'cancelled',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
