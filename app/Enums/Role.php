<?php

namespace App\Enums;

enum Role: string
{
    case MARKETING_MANAGER = 'marketing_manager';
    case MARKETING_COORDINATOR = 'marketing_coordinator';
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case GUEST = 'guest';

    /**
     * Get the formatted role name.
     */
    public function label(): string
    {
        return match ($this) {
            self::MARKETING_MANAGER => 'Marketing Manager',
            self::MARKETING_COORDINATOR => 'Marketing Coordinator',
            self::ADMIN => 'Admin',
            self::STUDENT => 'Student',
            self::GUEST => 'Guest',
        };
    }

    /**
     * Get all role values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all role labels.
     */
    public static function labels(): array
    {
        return array_map(fn($role) => $role->label(), self::cases());
    }
}
