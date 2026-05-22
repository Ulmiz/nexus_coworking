<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Recordatorios automáticos - se ejecuta diariamente a las 08:00
Schedule::command('reservations:send-reminders')->dailyAt('08:00');

// Para probar localmente:
//   php artisan reservations:send-reminders
//   php artisan schedule:work  (corre cada minuto y ejecuta las tareas que estén listas)
//   php artisan schedule:test  (Laravel 11+ - muestra comandos que se ejecutarían ahora)

