<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// User dashboard — admins/staff get redirected to admin dashboard
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    if ($user && ($user->isAdmin() || $user->isStaff())) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Bloque de rutas protegidas para usuarios autenticados
Route::middleware('auth')->group(function () {

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reservations (PDF, cleanup routes must be before resource to avoid conflicts)
    Route::get('reservations/{reservation}/pdf', [ReservationController::class, 'pdf'])->name('reservations.pdf');
    Route::delete('reservations/purge-cancelled', [ReservationController::class, 'purgeCancelled'])->name('reservations.purge-cancelled')->middleware('admin');
    Route::delete('reservations/{reservation}/force-destroy', [ReservationController::class, 'forceDestroy'])->name('reservations.force-destroy')->middleware('admin');
    Route::delete('reservations/{reservation}/delete-completed', [ReservationController::class, 'destroyCompleted'])->name('reservations.delete-completed');
    Route::resource('reservations', ReservationController::class);

    // Rooms
    Route::resource('rooms', RoomController::class);

    // ── Admin-only routes ────────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {

        // Admin dashboard & sub-pages
        Route::get('/admin',         [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/rooms',   [AdminController::class, 'rooms'])->name('admin.rooms');
        Route::get('/admin/users',   [AdminController::class, 'users'])->name('admin.users');

        // User management
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Keep old /users route pointing to admin users view for backwards compat
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');

    });
}); // Cierre del grupo de rutas 'auth'

// ── Ruta Mágica para arreglar la base de datos de Railway en vivo ──
Route::get('/arreglar-nexus', function () {
    try {
        DB::table('users')->update(['email_verified_at' => now()]);
        DB::table('notifications')->truncate();
        
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        
        return "¡Nexus arreglado! Usuarios verificados, notificaciones limpias y caché en cero.";
    } catch (\Exception $e) {
        return "Error al arreglar: " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';