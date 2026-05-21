<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Services\ReservationService;
use App\Services\PDFService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

/**
 * Controlador de Reservas
 * Gestiona todas las operaciones CRUD de reservas
 */
class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService,
        private PDFService $pdfService,
        private EmailService $emailService
    ) {}

    /**
     * Muestra la lista de reservas paginadas
     */
    public function index()
    {
       /** @var \App\Models\User $user */
$user = Auth::user();
        
        // Los clientes ven solo sus reservas, admins ven todas
        if ($user->isAdmin()) {
            $reservations = Reservation::with(['user', 'room'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $reservations = $user->reservations()
                ->with(['room'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Muestra el formulario para crear una nueva reserva
     */
    public function create(Request $request)
    {
        $rooms = Room::all();
        $selectedRoomId = $request->query('room');
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $users = $user->isAdmin() ? User::all() : collect();
        
        return view('reservations.create', compact('rooms', 'selectedRoomId', 'users'));
    }

    /**
     * Almacena una nueva reserva en la base de datos
     */
    public function store(StoreReservationRequest $request)
    {
        $room = Room::findOrFail($request->room_id);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $startTime = Carbon::createFromFormat('Y-m-d H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('Y-m-d H:i', $request->end_time);

        // Validar disponibilidad de la sala
        if (!$this->reservationService->isRoomAvailable($room, $startTime, $endTime)) {
            return back()->withErrors([
                'overlap' => 'La sala ya está reservada en ese horario. Por favor elige otro horario.',
            ])->withInput();
        }

        // Calcular precio
        $totalPrice = $this->reservationService->calculatePrice($room, $startTime, $endTime);

        // Crear reserva
        $reservation = Reservation::create([
            'user_id' => $user->isAdmin() && $request->has('user_id') ? $request->user_id : Auth::id(),
            'room_id' => $room->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_price' => $totalPrice,
            'status' => 'confirmed',
        ]);

        // Generar PDF
        $pdfContent = $this->pdfService->generateReservationReceipt($reservation);

        // Enviar email
        $this->emailService->sendReservationConfirmation($reservation, $pdfContent);

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva creada exitosamente. Te hemos enviado un correo con el comprobante.');
    }

    /**
     * Muestra los detalles de una reserva específica
     */
    public function show(Reservation $reservation)
    {
        Gate::authorize('view', $reservation);
        
        $reservation->load(['user', 'room']);
        
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Muestra el formulario para editar una reserva
     */
    public function edit(Reservation $reservation)
    {
        Gate::authorize('update', $reservation);
        
        if (!$reservation->canBeEdited()) {
            return back()->with('error', 'No se puede editar una reserva que ya ha comenzado.');
        }

        $rooms = Room::all();
        $reservation->load(['user', 'room']);
        
        return view('reservations.edit', compact('reservation', 'rooms'));
    }

    /**
     * Actualiza una reserva existente
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        Gate::authorize('update', $reservation);

        if (!$reservation->canBeEdited()) {
            return back()->with('error', 'No se puede editar una reserva que ya ha comenzado.');
        }

        $room = Room::findOrFail($request->room_id ?? $reservation->room_id);
        $startTime = Carbon::createFromFormat('Y-m-d H:i', $request->start_time ?? $reservation->start_time);
        $endTime = Carbon::createFromFormat('Y-m-d H:i', $request->end_time ?? $reservation->end_time);

        // Validar disponibilidad (excluir la reserva actual)
        if (!$this->reservationService->isRoomAvailable($room, $startTime, $endTime, $reservation)) {
            return back()->withErrors([
                'overlap' => 'La sala no está disponible en ese horario.',
            ])->withInput();
        }

        // Calcular nuevo precio
        $totalPrice = $this->reservationService->calculatePrice($room, $startTime, $endTime);

        // Actualizar reserva
        $reservation->update([
            'room_id' => $room->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_price' => $totalPrice,
            'status' => $request->status ?? $reservation->status,
        ]);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reserva actualizada exitosamente.');
    }

    /**
     * Cancela una reserva (soft delete lógico - cambia estado)
     */
    public function destroy(Reservation $reservation)
    {
        Gate::authorize('delete', $reservation);

        if (!$reservation->canBeCancelled()) {
            return back()->with('error', 'No se puede cancelar una reserva que ya ha finalizado.');
        }

        $reservation->update(['status' => 'cancelled']);
        
        $this->emailService->sendCancellationNotification($reservation);

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva cancelada exitosamente.');
    }

    /**
     * Descarga el PDF de la reserva
     */
    public function pdf(Reservation $reservation)
    {
        Gate::authorize('viewPDF', $reservation);

        $pdfContent = $this->pdfService->generateReservationReceipt($reservation);

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Comprobante_Reserva_' . $reservation->id . '.pdf"');
    }

    /**
     * Elimina permanentemente una reserva cancelada (solo admin)
     */
    public function forceDestroy(Reservation $reservation)
    {
        Gate::authorize('forceDelete', $reservation);

        if ($reservation->status !== 'cancelled') {
            return back()->with('error', 'Solo se pueden eliminar reservas canceladas.');
        }

        $reservation->forceDelete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva eliminada permanentemente.');
    }

    /**
     * Elimina una reserva finalizada (propietario o admin)
     */
    public function destroyCompleted(Reservation $reservation)
    {
        Gate::authorize('deleteCompleted', $reservation);

        if ($reservation->status !== 'confirmed' || !$reservation->end_time->isPast()) {
            return back()->with('error', 'Solo se pueden eliminar reservas finalizadas.');
        }

        $reservation->forceDelete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva finalizada eliminada correctamente.');
    }

    /**
     * Elimina todas las reservas canceladas (solo admin)
     */
    public function purgeCancelled()
    {
        Gate::authorize('isAdmin', Auth::user());

        $count = Reservation::where('status', 'cancelled')->forceDelete();

        return redirect()->route('reservations.index')
            ->with('success', "Se eliminaron {$count} reservas canceladas.");
    }
}