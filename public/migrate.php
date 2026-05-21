<?php
define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::capture();
$app->instance('request', $request);

header('Content-Type: text/plain; charset=utf-8');
echo "Ejecutando migraciones...\n\n";

$artisan = $kernel->getArtisan();

// Run migrations
$exitCode = $artisan->call('migrate', ['--force' => true]);
echo $artisan->output();

if ($exitCode === 0) {
    echo "\nMigraciones completadas exitosamente.\n";

    // Run seeders
    $exitCode = $artisan->call('db:seed', ['--force' => true]);
    echo $artisan->output();

    if ($exitCode === 0) {
        echo "\nSeeders completados exitosamente.\n";
        echo "\n======================\n";
        echo "Credenciales de prueba:\n";
        echo "Admin: admin@nexus.com / password\n";
        echo "Staff: staff@nexus.com / password\n";
        echo "Client: client@nexus.com / password\n";
        echo "======================\n";
        echo "\n✅ BORRÁ ESTE ARCHIVO (migrate.php) DESPUÉS DE USARLO\n";
    }
} else {
    echo "\nError al ejecutar migraciones.\n";
}
