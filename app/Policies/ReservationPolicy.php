<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use App\Enums\RoleEnum;

/**
 * Política de Autorización para Reservas
 * Define quién puede ver, editar o eliminar reservas
 */
class ReservationPolicy
{
    /**
     * Un usuario solo puede ver sus propias reservas,
     * excepto los admins que pueden ver todas
     */
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin() || $user->id === $reservation->user_id;
    }

    /**
     * Un usuario solo puede editar sus propias reservas,
     * y solo si no ha terminado aún
     */
    public function update(User $user, Reservation $reservation): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $reservation->user_id && 
               $reservation->end_time->isFuture();
    }

    /**
     * Un usuario solo puede cancelar sus propias reservas,
     * y solo si no ha terminado aún
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $reservation->user_id && 
               $reservation->end_time->isFuture();
    }

    /**
     * Un usuario puede ver el PDF de sus propias reservas,
     * excepto los admins que pueden ver todos
     */
    public function viewPDF(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin() || $user->id === $reservation->user_id;
    }
}
