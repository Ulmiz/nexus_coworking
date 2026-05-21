<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Admin overview dashboard.
     */
    public function dashboard()
    {
        $totalUsers        = User::count();
        $totalRooms        = Room::count();
        $totalReservations = Reservation::count();
        $todayReservations = Reservation::whereDate('start_time', today())->count();
        $rooms             = Room::all();

        // Today's schedule sorted by start time
        $todaySchedule = Reservation::with(['user', 'room'])
            ->whereDate('start_time', today())
            ->orderBy('start_time')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRooms',
            'totalReservations',
            'todayReservations',
            'rooms',
            'todaySchedule'
        ));
    }

    /**
     * Admin room management page.
     */
    public function rooms()
    {
        $rooms = Room::orderBy('name')->get();
        return view('admin.rooms', compact('rooms'));
    }

    /**
     * Admin user management page.
     */
    public function users()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }
}
