<?php

namespace App\Enums;

/**
 * Enumeración de Roles de Usuario
 * Define los roles disponibles en el sistema con constantes tipadas
 */
enum RoleEnum: string
{
    case ADMIN = 'admin';
    case STAFF = 'staff';
    case CLIENT = 'client';

    /**
     * Retorna todos los roles disponibles
     */
    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Verifica si un rol es administrador
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Verifica si un rol es staff
     */
    public function isStaff(): bool
    {
        return $this === self::STAFF;
    }

    /**
     * Verifica si un rol es cliente
     */
    public function isClient(): bool
    {
        return $this === self::CLIENT;
    }

    /**
     * Obtiene etiqueta legible del rol
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::STAFF => 'Personal',
            self::CLIENT => 'Cliente',
        };
    }
}
