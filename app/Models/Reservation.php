<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'room_id',
        'start_time',
        'end_time',
        'status',
        'total_price',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Verifica si la reserva puede ser cancelada
     * (No debe haber terminado aún)
     */
    public function canBeCancelled(): bool
    {
        return $this->status !== 'cancelled' && 
               $this->end_time->isFuture();
    }

    /**
     * Verifica si la reserva puede ser editada
     * (No debe haber terminado y no debe estar cancelada)
     */
    public function canBeEdited(): bool
    {
        return $this->status !== 'cancelled' && 
               $this->start_time->isFuture();
    }

    /**
     * Obtiene la duración de la reserva en horas
     */
    public function getDurationInHours(): float
    {
        return $this->start_time->diffInHours($this->end_time);
    }

    /**
     * Verifica si la reserva es para hoy
     */
    public function isToday(): bool
    {
        return $this->start_time->toDateString() === today()->toDateString();
    }

    /**
     * Verifica si la reserva está en progreso ahora
     */
    public function isInProgress(): bool
    {
        return now()->between($this->start_time, $this->end_time);
    }

    /**
     * Verifica si la reserva es próxima (dentro de 24 horas)
     */
    public function isUpcoming(): bool
    {
        return $this->start_time->isFuture() && 
               $this->start_time->diffInHours(now()) <= 24;
    }

    /**
     * Formatea el estado de forma legible
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmada',
            'cancelled' => 'Cancelada',
            default => $this->status,
        };
    }
}
