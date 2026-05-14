<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')->with('success', 'Sala creada exitosamente.');
    }

    public function destroy(Room $room)
    {
        $room->delete(); // Soft Delete
        return redirect()->route('rooms.index')->with('success', 'Sala eliminada (soft delete).');
    }
}
