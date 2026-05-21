<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth; // <-- Importamos el Facade Auth
use Carbon\Carbon;

/**
 * Controlador de Administración
 * Gestiona los paneles y vistas administrativas
 */
class AdminController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    /**
     * Panel administrativo principal
     * Muestra estadísticas y resumen del sistema
     */
    public function dashboard()
    {
        // Cambiamos auth()->check() por Auth::check()
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cambiamos auth()->user() por Auth::user()
        Gate::authorize('isAdmin', Auth::user());

        $totalUsers = User::count();
        $totalRooms = Room::count();
        $totalReservations = Reservation::count();
        $todayReservations = Reservation::whereDate('start_time', today())
            ->where('status', 'confirmed')
            ->count();

        // Reservas de hoy ordenadas por hora
        $todaySchedule = Reservation::with(['user', 'room'])
            ->whereDate('start_time', today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();

        // Salas disponibles hoy
        $rooms = Room::all();

        // Próximas reservas en general
        $upcomingReservations = Reservation::with(['user', 'room'])
            ->where('start_time', '>=', now())
            ->where('status', 'confirmed')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRooms',
            'totalReservations',
            'todayReservations',
            'todaySchedule',
            'rooms',
            'upcomingReservations'
        ));
    }

    /**
     * Gestión de salas para administrador
     */
    public function rooms()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Gate::authorize('isAdmin', Auth::user());

        $rooms = Room::orderBy('name')
            ->withCount('reservations')
            ->get();

        return view('admin.rooms', compact('rooms'));
    }

    /**
     * Gestión de usuarios para administrador
     */
    public function users()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Gate::authorize('isAdmin', Auth::user());

        $users = User::orderBy('name')
            ->withCount('reservations')
            ->get();

        return view('admin.users', compact('users'));
    }
}