<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Notifications\ReservationReminder;
use App\Services\PDFService;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Comando: reservations:send-reminders
 * 
 * Envía recordatorios automáticos a clientes con reservas programadas
 * para el día siguiente.
 * 
 * Se ejecuta diariamente a las 08:00 (configurado en routes/console.php)
 * 
 * Uso: php artisan reservations:send-reminders
 */
class SendReservationReminders extends Command
{
    protected $signature = 'reservations:send-reminders';

    protected $description = 'Envía recordatorios automáticos a clientes con reservas para el día siguiente.';

    public function __construct(
        private PDFService $pdfService,
        private EmailService $emailService,
    ) {
        parent::__construct();
    }

    /**
     * Ejecuta el comando
     */
    public function handle()
    {
        $this->info('🚀 Iniciando envío de recordatorios de reservas...');

        $tomorrow = Carbon::tomorrow();

        // Obtener todas las reservas confirmadas para mañana
        $reservations = Reservation::with(['user', 'room'])
            ->whereDate('start_time', $tomorrow->toDateString())
            ->where('status', 'confirmed')
            ->get();

        if ($reservations->isEmpty()) {
            $this->info('✅ No hay reservas para mañana.');
            Log::info('SendReservationReminders: No hay reservas programadas para mañana.');
            return;
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($reservations as $reservation) {
            try {
                // Validar que el usuario tenga email
                if (!$reservation->user || !$reservation->user->email) {
                    $this->warn("⚠️  Reserva {$reservation->id}: Usuario sin email");
                    $errorCount++;
                    continue;
                }

                // Notificación en app
                $reservation->user->notify(new ReservationReminder($reservation));

                // Generar PDF
                $pdfContent = $this->pdfService->generateReservationReceipt($reservation);

                // Enviar email
                $success = $this->emailService->sendReservationReminder($reservation, $pdfContent);

                if ($success) {
                    $this->line("✓ Recordatorio enviado a: {$reservation->user->email}");
                    $successCount++;
                } else {
                    $this->warn("✗ Error al enviar recordatorio a: {$reservation->user->email}");
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $this->error("✗ Excepción en reserva {$reservation->id}: " . $e->getMessage());
                $errorCount++;
                Log::error('Error en SendReservationReminders', [
                    'reservation_id' => $reservation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Resumen final
        $this->info("\n📊 Resumen:");
        $this->info("✓ Enviados exitosamente: {$successCount}");
        $this->info("✗ Errores: {$errorCount}");
        $this->info("📅 Total reservas para mañana: " . $reservations->count());

        Log::info('SendReservationReminders completado', [
            'success' => $successCount,
            'errors' => $errorCount,
            'total' => $reservations->count(),
        ]);
    }
}
