<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmed;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SendReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía un recordatorio a los clientes con reservas para el día siguiente.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow();

        $reservations = Reservation::with(['user', 'room'])
            ->whereDate('start_time', $tomorrow)
            ->where('status', 'confirmed')
            ->get();

        if ($reservations->isEmpty()) {
            $this->info('No hay reservas para mañana.');
            return;
        }

        $count = 0;
        foreach ($reservations as $reservation) {
            if ($reservation->user && $reservation->user->email) {
                // Se reutiliza la plantilla de confirmación como recordatorio (adjuntando el PDF)
                $pdf = Pdf::loadView('pdf.reservation_receipt', compact('reservation'));
                $pdfContent = $pdf->output();

                Mail::to($reservation->user->email)->send(new ReservationConfirmed($reservation, $pdfContent));
                $count++;
            }
        }

        $this->info("Se enviaron {$count} recordatorios exitosamente.");
    }
}
