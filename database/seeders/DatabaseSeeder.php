<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Usuarios por Defecto
        User::updateOrCreate(['email' => 'admin@nexus.com'], [
            'name' => 'Administrador',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::updateOrCreate(['email' => 'staff@nexus.com'], [
            'name' => 'Staff Nexus',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        User::updateOrCreate(['email' => 'client@nexus.com'], [
            'name' => 'Cliente Vip',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);

        // 2. Crear Salas de Coworking
        \App\Models\Room::updateOrCreate(['name' => 'Sala Ejecutiva A'], [
            'description' => 'Oficina privada con vista panorámica, ideal para reuniones de directorio. Incluye proyector 4K y Apple TV.',
            'capacity' => 12,
            'price_per_hour' => 45.00,
        ]);

        \App\Models\Room::updateOrCreate(['name' => 'Estudio Creativo'], [
            'description' => 'Espacio diseñado para lluvia de ideas. Pizarra de cristal en toda la pared y mobiliario ergonómico.',
            'capacity' => 6,
            'price_per_hour' => 25.00,
        ]);

        \App\Models\Room::updateOrCreate(['name' => 'Cabina Individual (Pod)'], [
            'description' => 'Cabina insonorizada para videollamadas o trabajo de alta concentración. Internet dedicado de 1Gbps.',
            'capacity' => 1,
            'price_per_hour' => 10.00,
        ]);
    }
}
