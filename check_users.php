<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$users = \App\Models\User::all(['id', 'name', 'email', 'role']);
foreach ($users as $u) {
    echo "{$u->id}: {$u->name} <{$u->email}> role={$u->role}\n";
}
echo "Total: " . \App\Models\User::count() . "\n";
