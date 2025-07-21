<?php

declare(strict_types=1);

namespace App\Enums;

enum MenuPlacement: string
{
    case HEADER = 'header';
    case FOOTER_SECTION_1 = 'footer_section_1';
    case FOOTER_SECTION_2 = 'footer_section_2';
    case USER_DASHBOARD = 'user_dashboard';

    public static function forSelect(): array
    {
        return collect(self::cases())->map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ])->toArray();
    }

    public function label(): string
    {
        return match($this) {
            self::HEADER => 'Header Navigation',
            self::FOOTER_SECTION_1 => 'Footer Section 1',
            self::FOOTER_SECTION_2 => 'Footer Section 2',
            self::USER_DASHBOARD => 'User Dashboard',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::HEADER => 'menu',
            self::FOOTER_SECTION_1 => 'article',
            self::FOOTER_SECTION_2 => 'info',
            self::USER_DASHBOARD => 'dashboard',
        };
    }
}
