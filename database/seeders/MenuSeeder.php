<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MenuPlacement;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->createMainNavigation();
        $this->createFooterNavigation();
        $this->createUserNavigation();
    }

    private function createMainNavigation(): void
    {
        // Main Navigation
        $menuItems = [
            [
                'name' => 'Home',
                'label' => 'Home',
                'url' => '/',
                'icon' => 'home',
                'sort_order' => 1,
            ],
            [
                'name' => 'Menu',
                'label' => 'Our Menu',
                'url' => '/menu',
                'icon' => 'restaurant_menu',
                'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Breakfast',
                        'label' => 'Breakfast Menu',
                        'url' => '/menu/breakfast',
                        'icon' => 'breakfast_dining',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Lunch',
                        'label' => 'Lunch Menu',
                        'url' => '/menu/lunch',
                        'icon' => 'lunch_dining',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Dinner',
                        'label' => 'Dinner Menu',
                        'url' => '/menu/dinner',
                        'icon' => 'dinner_dining',
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'name' => 'About',
                'label' => 'About Us',
                'url' => '/about',
                'icon' => 'info',
                'sort_order' => 3,
            ],
            [
                'name' => 'Contact',
                'label' => 'Contact Us',
                'url' => '/contact',
                'icon' => 'contact_support',
                'sort_order' => 4,
            ],
        ];

        foreach ($menuItems as $item) {
            $children = $item['children'] ?? [];
            unset($item['children']);

            $parent = Menu::create(array_merge($item, [
                'type' => 'link',
                'placement' => MenuPlacement::HEADER,
                'new_window' => false,
                'status' => true,
            ]));

            foreach ($children as $child) {
                Menu::create(array_merge($child, [
                    'type' => 'link',
                    'placement' => MenuPlacement::HEADER,
                    'parent_id' => $parent->id,
                    'new_window' => false,
                    'status' => true,
                ]));
            }
        }
    }

    private function createFooterNavigation(): void
    {
        // Footer Links
        $footerSections = [
            MenuPlacement::FOOTER_SECTION_1 => [
                [
                    'name' => 'About',
                    'label' => 'About Us',
                    'url' => '/about',
                    'icon' => 'info',
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Contact',
                    'label' => 'Contact Us',
                    'url' => '/contact',
                    'icon' => 'contact_support',
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Terms',
                    'label' => 'Terms & Conditions',
                    'url' => '/terms',
                    'icon' => 'gavel',
                    'sort_order' => 3,
                ],
            ],
            MenuPlacement::FOOTER_SECTION_2 => [
                [
                    'name' => 'Privacy',
                    'label' => 'Privacy Policy',
                    'url' => '/privacy',
                    'icon' => 'security',
                    'sort_order' => 1,
                ],
                [
                    'name' => 'FAQ',
                    'label' => 'FAQs',
                    'url' => '/faq',
                    'icon' => 'help',
                    'sort_order' => 2,
                ],
            ],
        ];

        foreach ($footerSections as $placement => $items) {
            foreach ($items as $item) {
                Menu::create(array_merge($item, [
                    'type' => 'link',
                    'placement' => $placement,
                    'new_window' => false,
                    'status' => true,
                ]));
            }
        }
    }

    private function createUserNavigation(): void
    {
        // User Dashboard Navigation
        $userMenuItems = [
            [
                'name' => 'Dashboard',
                'label' => 'My Dashboard',
                'url' => '/dashboard',
                'icon' => 'dashboard',
                'sort_order' => 1,
            ],
            [
                'name' => 'Orders',
                'label' => 'My Orders',
                'url' => '/orders',
                'icon' => 'receipt',
                'sort_order' => 2,
            ],
            [
                'name' => 'Profile',
                'label' => 'My Profile',
                'url' => '/profile',
                'icon' => 'person',
                'sort_order' => 3,
            ],
        ];

        foreach ($userMenuItems as $item) {
            Menu::create(array_merge($item, [
                'type' => 'link',
                'placement' => MenuPlacement::USER_DASHBOARD,
                'new_window' => false,
                'status' => true,
            ]));
        }
    }
}
