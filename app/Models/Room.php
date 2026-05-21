<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'capacity',
        'price_per_hour',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Obtiene las reservas activas (no canceladas) de esta sala
     */
    public function activeReservations()
    {
        return $this->hasMany(Reservation::class)
            ->where('status', '!=', 'cancelled');
    }

    /**
     * Obtiene las reservas confirmadas de esta sala
     */
    public function confirmedReservations()
    {
        return $this->hasMany(Reservation::class)
            ->where('status', 'confirmed');
    }

    /**
     * Verifica si la sala está disponible en un rango de horas
     * 
     * @param \Carbon\Carbon $startTime
     * @param \Carbon\Carbon $endTime
     * @return bool
     */
    public function isAvailable(\Carbon\Carbon $startTime, \Carbon\Carbon $endTime): bool
    {
        return !$this->activeReservations()
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                      });
            })
            ->exists();
    }

    /**
     * Obtiene el porcentaje de ocupación de la sala para hoy
     */
    public function getTodayOccupancyPercentage(): float
    {
        $reservations = $this->activeReservations()
            ->whereDate('start_time', today())
            ->get();

        $businessHours = 10; // 8:00 a 18:00
        $occupiedHours = 0;

        foreach ($reservations as $reservation) {
            $occupiedHours += $reservation->start_time->diffInHours($reservation->end_time);
        }

        return min(100, ($occupiedHours / $businessHours) * 100);
    }
}

