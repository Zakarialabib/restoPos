<?php

declare(strict_types=1);

namespace App\Enums;

enum MenuPlacement: string
{
    case HEADER = 'header';
    case FOOTER_SECTION_1 = 'footer_section_1';
    case FOOTER_SECTION_2 = 'footer_section_2';
    case USER_DASHBOARD = 'user_dashboard';
    case SIDEBAR = 'sidebar';
    case MEGA_MENU = 'mega_menu';
    case BREADCRUMB = 'breadcrumb';
    case MOBILE_ONLY = 'mobile_only';
    case DESKTOP_ONLY = 'desktop_only';
    case THEME_PRIMARY = 'theme_primary';
    case THEME_SECONDARY = 'theme_secondary';
    case THEME_UTILITY = 'theme_utility';

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
            self::SIDEBAR => 'Sidebar Navigation',
            self::MEGA_MENU => 'Mega Menu',
            self::BREADCRUMB => 'Breadcrumb Navigation',
            self::MOBILE_ONLY => 'Mobile Only',
            self::DESKTOP_ONLY => 'Desktop Only',
            self::THEME_PRIMARY => 'Theme Primary Menu',
            self::THEME_SECONDARY => 'Theme Secondary Menu',
            self::THEME_UTILITY => 'Theme Utility Menu',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::HEADER => 'menu',
            self::FOOTER_SECTION_1 => 'article',
            self::FOOTER_SECTION_2 => 'info',
            self::USER_DASHBOARD => 'dashboard',
            self::SIDEBAR => 'sidebar',
            self::MEGA_MENU => 'grid-3x3',
            self::BREADCRUMB => 'chevron-right',
            self::MOBILE_ONLY => 'smartphone',
            self::DESKTOP_ONLY => 'monitor',
            self::THEME_PRIMARY => 'star',
            self::THEME_SECONDARY => 'circle',
            self::THEME_UTILITY => 'settings',
        };
    }
}
