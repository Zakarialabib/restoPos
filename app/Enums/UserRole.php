<?php

namespace App\Enums;

enum UserRole : string
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
        return $role === self::ADMIN;
    }

    public static function isStaff(string $role): bool
    {
        return $role === self::STAFF;
    }

    public static function isUser(string $role): bool
    {
        return $role === self::USER;
    }
}
