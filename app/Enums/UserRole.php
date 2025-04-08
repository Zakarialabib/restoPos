<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case STAFF = 'staff';
    case USER = 'user';

    public static function getRoles(): array
    {
        return [
            self::ADMIN, self::STAFF, self::USER
        ];
    }

    public static function isAdmin(string $role): bool
    {
        return self::ADMIN === $role;
    }

    public static function isStaff(string $role): bool
    {
        return self::STAFF === $role;
    }

    public static function isUser(string $role): bool
    {
        return self::USER === $role;
    }
}
