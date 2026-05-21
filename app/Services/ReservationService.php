<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Servicio de Reservas
 * Encapsula toda la lógica de negocio relacionada con reservas
 */
class ReservationService
{
    /**
     * Calcula el precio total de una reserva
     *
     * @param Room $room
     * @param Carbon $startTime
     * @param Carbon $endTime
     * @return float
     */
    public function calculatePrice(Room $room, Carbon $startTime, Carbon $endTime): float
    {
        $hours = $endTime->diffInHours($startTime);
        return $hours * $room->price_per_hour;
    }

    /**
     * Valida que no exista sobreposición de horarios
     * Excluye reservas canceladas y la reserva actual (si es update)
     *
     * @param Room $room
     * @param Carbon $startTime
     * @param Carbon $endTime
     * @param Reservation|null $excludeReservation
     * @return bool
     */
    public function isRoomAvailable(
        Room $room,
        Carbon $startTime,
        Carbon $endTime,
        ?Reservation $excludeReservation = null
    ): bool {
        $query = Reservation::where('room_id', $room->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                      });
            });

        if ($excludeReservation) {
            $query->where('id', '!=', $excludeReservation->id);
        }

        return !$query->exists();
    }

    /**
     * Obtiene las próximas reservas confirmadas de una sala
     *
     * @param Room $room
     * @param int $limit
     * @return Collection
     */
    public function getUpcomingReservations(Room $room, int $limit = 5): Collection
    {
        return $room->reservations()
            ->where('status', 'confirmed')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtiene el horario de una sala para una fecha específica
     *
     * @param Room $room
     * @param Carbon $date
     * @return Collection
     */
    public function getDaySchedule(Room $room, Carbon $date): Collection
    {
        return $room->reservations()
            ->whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Verifica si una reserva puede ser cancelada
     *
     * @param Reservation $reservation
     * @return bool
     */
    public function canCancel(Reservation $reservation): bool
    {
        return $reservation->status !== 'cancelled' && 
               $reservation->end_time->isFuture();
    }

    /**
     * Obtiene las slots libres de una sala en un día
     *
     * @param Room $room
     * @param Carbon $date
     * @param int $intervalMinutes
     * @return array
     */
    public function getAvailableSlots(Room $room, Carbon $date, int $intervalMinutes = 60): array
    {
        $schedule = $this->getDaySchedule($room, $date);
        $slots = [];

        $businessStart = $date->clone()->setHour(8)->setMinute(0);
        $businessEnd = $date->clone()->setHour(18)->setMinute(0);

        $currentTime = $businessStart;

        while ($currentTime->isBefore($businessEnd)) {
            $endTime = $currentTime->clone()->addMinutes($intervalMinutes);

            $isAvailable = !$schedule->some(function ($reservation) use ($currentTime, $endTime) {
                return $currentTime->between($reservation->start_time, $reservation->end_time) ||
                       $endTime->between($reservation->start_time, $reservation->end_time);
            });

            if ($isAvailable) {
                $slots[] = [
                    'start' => $currentTime->format('Y-m-d H:i'),
                    'end' => $endTime->format('Y-m-d H:i'),
                ];
            }

            $currentTime = $endTime;
        }

        return $slots;
    }
}
