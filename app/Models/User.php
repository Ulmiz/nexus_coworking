<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // ¡Añadido para evitar errores al registrar usuarios!
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación: Un usuario tiene muchas reservas
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Verifica si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verifica si el usuario es staff
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Verifica si el usuario es cliente
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Verifica si el usuario puede hacer reservas
     */
    public function canReservate(): bool
    {
        return in_array($this->role, ['staff', 'client']);
    }
}