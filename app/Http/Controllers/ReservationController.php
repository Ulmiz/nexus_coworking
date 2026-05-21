<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmed;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'room'])->orderBy('created_at', 'desc')->paginate(10);
        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $rooms = Room::all();
        $selectedRoomId = $request->query('room');
        return view('reservations.create', compact('rooms', 'selectedRoomId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ], [
            'room_id.required' => 'Debes seleccionar una sala.',
            'room_id.exists' => 'La sala seleccionada no es válida.',
            'start_time.required' => 'La fecha y hora de inicio son obligatorias.',
            'start_time.date' => 'El formato de la fecha de inicio es incorrecto.',
            'start_time.after' => 'La hora de inicio debe ser posterior a la fecha y hora actual.',
            'end_time.required' => 'La fecha y hora de fin son obligatorias.',
            'end_time.date' => 'El formato de la fecha de fin es incorrecto.',
            'end_time.after' => 'La hora de término debe ser posterior a la hora de inicio.',
        ]);

        // Validación de cruce de horarios
        $exists = Reservation::where('room_id', $request->room_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->exists();

        if ($exists) {
            return back()->withErrors(['overlap' => 'La sala ya está reservada en ese horario.']);
        }

        $room = Room::find($request->room_id);
        $hours = (strtotime($request->end_time) - strtotime($request->start_time)) / 3600;
        $total_price = $hours * $room->price_per_hour;

        $reservation = Reservation::create([
            'user_id' => Auth::id() ?? 1, // Fallback para pruebas si no hay auth
            'room_id' => $request->room_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $total_price,
            'status' => 'confirmed',
        ]);

        // Generar PDF
        $pdf = Pdf::loadView('pdf.reservation_receipt', compact('reservation'));
        $pdfContent = $pdf->output();

        // Enviar Email con PDF adjunto
        // Se ejecuta solo si hay un usuario autenticado para no fallar en pruebas sin login
        if (Auth::check()) {
            Mail::to(Auth::user()->email)->send(new ReservationConfirmed($reservation, $pdfContent));
        }

        return redirect()->route('reservations.index')->with('success', 'Reserva creada exitosamente. Te hemos enviado un correo con el PDF.');
    }
    // Cancelar reserva (cambia estado a cancelled)
    public function destroy(Reservation $reservation)
    {
        // Solo permite cancelar si aún no ha terminado
        $now = now();
        if ($reservation->end_time->isPast()) {
            return back()->with('error', 'No se puede cancelar una reserva que ya ha finalizado.');
        }
        $reservation->update(['status' => 'cancelled']);
        return back()->with('success', 'Reserva cancelada exitosamente.');
    }
}
