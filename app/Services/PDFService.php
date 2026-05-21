<?php

namespace App\Services;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio de Generación de PDFs
 * Centraliza la lógica de generación de documentos
 */
class PDFService
{
    /**
     * Genera el PDF de comprobante de reserva
     *
     * @param Reservation $reservation
     * @return string Contenido del PDF en bytes
     */
    public function generateReservationReceipt(Reservation $reservation): string
    {
        $pdf = Pdf::loadView('pdf.reservation_receipt', [
            'reservation' => $reservation,
        ]);

        return $pdf->output();
    }

    /**
     * Genera y almacena el PDF de reserva
     *
     * @param Reservation $reservation
     * @return string Ruta del archivo almacenado
     */
    public function storeReservationReceipt(Reservation $reservation): string
    {
        $pdfContent = $this->generateReservationReceipt($reservation);
        
        $fileName = sprintf(
            'reservations/receipt_%d_%s.pdf',
            $reservation->id,
            now()->format('Y-m-d_H-i-s')
        );

        Storage::disk('private')->put($fileName, $pdfContent);

        return $fileName;
    }

    /**
     * Genera PDF de reporte de reservas
     *
     * @param array $reservations
     * @return string Contenido del PDF en bytes
     */
    public function generateReservationsReport(array $reservations): string
    {
        $pdf = Pdf::loadView('pdf.reservations_report', [
            'reservations' => $reservations,
            'generatedAt' => now(),
        ]);

        return $pdf->output();
    }
}
