<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use App\Enums\RoleEnum;

/**
 * Política de Autorización para Salas
 * Define quién puede crear, editar o eliminar salas
 */
class RoomPolicy
{
    /**
     * Solo admins pueden crear salas
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admins pueden editar salas
     */
    public function update(User $user, Room $room): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admins pueden eliminar (soft delete) salas
     */
    public function delete(User $user, Room $room): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admins pueden restaurar salas eliminadas
     */
    public function restore(User $user, Room $room): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admins pueden eliminar permanentemente salas
     */
    public function forceDelete(User $user, Room $room): bool
    {
        return $user->isAdmin();
    }
}
