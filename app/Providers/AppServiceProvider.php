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
        // Registrar Policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Room::class, RoomPolicy::class);
        Gate::policy(Reservation::class, ReservationPolicy::class);

        // Gate helper para verificar si es admin
        Gate::define('isAdmin', function (User $user) {
            return $user->isAdmin();
        });

        // Gate helper para verificar si puede reservar
        Gate::define('canReservate', function (User $user) {
            return $user->canReservate();
        });
    }
}

