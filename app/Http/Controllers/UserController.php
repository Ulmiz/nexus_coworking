<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
     * Almacena un nuevo usuario (solo admin)
     */
    public function store(Request $request)
    {
        Gate::authorize('isAdmin', $request->user());

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'lowercase', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'staff', 'client'])],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Ya existe un usuario con ese correo.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol debe ser: admin, staff o client.',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', "Usuario {$data['name']} creado exitosamente.");
    }

    /**
     * Elimina un usuario (solo admin)
     */
    public function destroy(Request $request, User $user)
    {
        Gate::authorize('isAdmin', $request->user());

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Usuario {$user->name} eliminado.");
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

