<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// User dashboard (requires auth + verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reservations (PDF route must be before resource to avoid conflicts)
    Route::get('reservations/{reservation}/pdf', [ReservationController::class, 'pdf'])->name('reservations.pdf');
    Route::resource('reservations', ReservationController::class);

    // Rooms
    Route::resource('rooms', RoomController::class);

    // ── Admin-only routes ────────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {

        // Admin dashboard & sub-pages
        Route::get('/admin',         [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/rooms',   [AdminController::class, 'rooms'])->name('admin.rooms');
        Route::get('/admin/users',   [AdminController::class, 'users'])->name('admin.users');

        // Role management (PATCH)
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');

        // Keep old /users route pointing to admin users view for backwards compat
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    });
});

require __DIR__.'/auth.php';
