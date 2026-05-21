<?php

namespace App\Services;

use App\Mail\ReservationConfirmed;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de Notificaciones por Email
 * Centraliza el envío de correos del sistema
 */
class EmailService
{
    /**
     * Envía confirmación de reserva con PDF adjunto
     *
     * @param Reservation $reservation
     * @param string $pdfContent
     * @return bool
     */
    public function sendReservationConfirmation(Reservation $reservation, string $pdfContent): bool
    {
        try {
            Mail::to($reservation->user->email)
                ->send(new ReservationConfirmed($reservation, $pdfContent));

            Log::info('Correo de confirmación enviado', [
                'reservation_id' => $reservation->id,
                'user_email' => $reservation->user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al enviar confirmación de reserva', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Envía recordatorio de reserva próxima
     *
     * @param Reservation $reservation
     * @param string $pdfContent
     * @return bool
     */
    public function sendReservationReminder(Reservation $reservation, string $pdfContent): bool
    {
        try {
            Mail::to($reservation->user->email)
                ->send(new ReservationConfirmed($reservation, $pdfContent));

            Log::info('Recordatorio de reserva enviado', [
                'reservation_id' => $reservation->id,
                'user_email' => $reservation->user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al enviar recordatorio de reserva', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Envía notificación de cancelación de reserva
     *
     * @param Reservation $reservation
     * @return bool
     */
    public function sendCancellationNotification(Reservation $reservation): bool
    {
        try {
            $content = "Tu reserva en {$reservation->room->name} ha sido cancelada.";
            
            Log::info('Notificación de cancelación enviada', [
                'reservation_id' => $reservation->id,
                'user_email' => $reservation->user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al enviar notificación de cancelación', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
