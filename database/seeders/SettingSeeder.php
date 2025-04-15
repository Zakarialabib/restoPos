<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    protected $settings = [
        [
            'key'   => 'company_name',
            'value' => 'RestoPos',
        ],
        [
            'key'   => 'site_title',
            'value' => 'RestoPos',
        ],
        [
            'key'   => 'company_email_address',
            'value' => 'zakarialabib@gmail.com',
        ],
        [
            'key'   => 'company_phone',
            'value' => '+212638041919',
        ],
        [
            'key'   => 'company_address',
            'value' => 'Casablanca, Maroc',
        ],
        [
            'key'   => 'company_tax',
            'value' => '123456789',
        ],
        [
            'key'   => 'site_logo',
            'value' => 'logo.svg',
        ],
        [
            'key'   => 'site_favicon',
            'value' => '',
        ],
        // Visual Identity
        ['key' => 'primary_color', 'value' => '#FF6B35'],
        ['key' => 'secondary_color', 'value' => '#00A896'],
        ['key' => 'font_family', 'value' => 'Inter'],
        // Theme Switching
        // [
        //     'key' => 'available_themes',
        //     'value' => json_encode(['light', 'dark', 'nature']),
        // ],
        [
            'key' => 'default_theme',
            'value' => 'light'
        ],
        [
            'key'   => 'footer_copyright_text',
            'value' => '',
        ],
        [
            'key'   => 'multi_language',
            'value' => true,
        ],
        [
            'key'   => 'seo_meta_title',
            'value' => 'RestoPos',
        ],
        [
            'key'   => 'seo_meta_description',
            'value' => 'RestoPos',
        ],
        [
            'key'   => 'social_facebook',
            'value' => 'https://www.facebook.com/zakaria.labiib/',
        ],
        [
            'key'   => 'social_twitter',
            'value' => 'https://twitter.com/zakarialabib',
        ],
        [
            'key'   => 'social_tiktok',
            'value' => '#',
        ],
        [
            'key'   => 'social_instagram',
            'value' => '#',
        ],
        [
            'key'   => 'social_linkedin',
            'value' => 'https://www.linkedin.com/in/zakaria-labib/',
        ],
        [
            'key'   => 'social_whatsapp',
            'value' => '#',
        ],
        [
            'key'   => 'head_tags',
            'value' => '',
        ],
        [
            'key'   => 'body_tags',
            'value' => '',
        ],
        [
            'key'   => 'header_bg_color',
            'value' => '#ffffff',
        ],
        [
            'key'   => 'footer_bg_color',
            'value' => '#ffffff',
        ],
        [
            'key'   => 'site_maintenance_message',
            'value' => 'Site is under maintenance',
        ],
        [
            'key'   => 'whatsapp_custom_message',
            'value' => "Salam, J'ai une Question/Demande d'nformation",
        ],
        [
            'key'   => 'homepage_type',
            'value' => 'welcome',
        ],
        // Banner Settings
        [
            'key'   => 'show_promotional_banner',
            'value' => true,
        ],
        [
            'key'   => 'banner_title',
            'value' => 'Craft Your Perfect Juice',
        ],
        [
            'key'   => 'banner_description',
            'value' => 'Select from the freshest ingredients and design your custom juice blend with our easy-to-use system. Pure refreshment awaits!',
        ],
        [
            'key'   => 'banner_button_text',
            'value' => 'Make Your Juice Now',
        ],
        [
            'key'   => 'banner_button_url',
            'value' => '/compose/fruits',
        ],
        [
            'key'   => 'banner_image',
            'value' => 'images/make-juice.webp',
        ],
        [
            'key'   => 'is_installed',
            'value' => false,
        ],
        [
            'key'   => 'installation_step',
            'value' => 1,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $index => $setting) {
            $result = Settings::create($setting);

            if (! $result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }
        $this->command->info('Inserted ' . count($this->settings) . ' records');
    }
}
