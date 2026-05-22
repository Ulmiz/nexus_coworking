<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Room;
use App\Models\Reservation;
use App\Policies\UserPolicy;
use App\Policies\RoomPolicy;
use App\Policies\ReservationPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL; // <-- OJO: Se agregó esta línea aquí arriba

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- NUEVO: Forzar HTTPS en producción ---
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        // -----------------------------------------

        // Registrar Policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Room::class, RoomPolicy::class);
        Gate::policy(Reservation::class, ReservationPolicy::class);

        // Gate helper para verificar si es admin o staff
        Gate::define('isAdmin', function (User $user) {
            return $user->isAdmin() || $user->isStaff();
        });

        // Gate helper para verificar si puede reservar
        Gate::define('canReservate', function (User $user) {
            return $user->canReservate();
        });
    }
}