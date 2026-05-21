<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use Illuminate\Support\Facades\Gate;

/**
 * Controlador de Salas
 * Gestiona todas las operaciones CRUD de salas de coworking
 */
class RoomController extends Controller
{
    /**
     * Muestra la lista de todas las salas disponibles
     */
    public function index()
    {
        $rooms = Room::orderBy('name')->get();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Muestra el formulario para crear una nueva sala
     */
    public function create()
    {
        Gate::authorize('create', Room::class);
        
        return view('rooms.create');
    }

    /**
     * Almacena una nueva sala en la base de datos
     */
    public function store(StoreRoomRequest $request)
    {
        Gate::authorize('create', Room::class);

        Room::create($request->validated());

        return redirect()->route('rooms.index')
            ->with('success', 'Sala creada exitosamente.');
    }

    /**
     * Muestra los detalles de una sala específica
     */
    public function show(Room $room)
    {
        $room->load(['reservations']);
        return view('rooms.show', compact('room'));
    }

    /**
     * Muestra el formulario para editar una sala
     */
    public function edit(Room $room)
    {
        Gate::authorize('update', $room);
        
        return view('rooms.edit', compact('room'));
    }

    /**
     * Actualiza una sala existente
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        Gate::authorize('update', $room);

        $room->update($request->validated());

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Sala actualizada exitosamente.');
    }

    /**
     * Elimina una sala (soft delete)
     */
    public function destroy(Room $room)
    {
        Gate::authorize('delete', $room);

        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Sala eliminada exitosamente.');
    }
}

