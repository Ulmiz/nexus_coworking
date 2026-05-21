<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Controlador de Usuarios
 * Gestiona las operaciones relacionadas con usuarios
 */
class UserController extends Controller
{
    /**
     * Muestra la lista de todos los usuarios
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Actualiza el rol de un usuario
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('updateRole', $user);

        $request->validate([
            'role' => 'required|in:admin,staff,client',
        ], [
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol debe ser: admin, staff o client.',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('users.index')
            ->with('success', sprintf('Rol de %s actualizado a %s.', $user->name, $request->role));
    }
}

