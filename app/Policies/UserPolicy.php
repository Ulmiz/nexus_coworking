<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleEnum;

/**
 * Política de Autorización para Usuarios
 * Define quién puede ver, editar o eliminar usuarios
 */
class UserPolicy
{
    /**
     * Solo admins pueden ver la lista de usuarios
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Un usuario puede ver su propio perfil o cualquier admin puede ver cualquier perfil
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Solo admins pueden cambiar roles
     */
    public function updateRole(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Un usuario puede editar su propio perfil,
     * y los admins pueden editar cualquier perfil
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Solo admins pueden eliminar (soft delete) usuarios
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Solo admins pueden restaurar usuarios
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
