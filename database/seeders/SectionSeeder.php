<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageType;
use App\Models\Language;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run(): void
    {
        $language = Language::where('is_default', true)->first();

        $sections = [
            [
                'id'             => 1,
                'title'          => 'Signature Blends',
                'featured_title' => 'Our Most Popular Creations',
                'subtitle'       => 'Ready-to-order favorites crafted from customer favorites',
                'label'          => 'View Menu',
                'link'           => '/menu',
                'description'    => 'Skip the composition and enjoy our expertly crafted signature blends. Each recipe is based on the most popular combinations ordered by our customers.',
                'status'         => '1',
                'bg_color'       => '#f8fafc',
                'text_color'     => '#1f2937',
                'position'       => '2',
                'language_id'    => $language->id,
                'type'           => PageType::MARKETING_PRODUCT,
            ],
            [
                'id'             => 2,
                'title'          => 'Customer Favorites',
                'featured_title' => 'Most Ordered Combinations',
                'subtitle'       => 'See what others are loving',
                'label'          => 'Try Popular',
                'link'           => '/menu?filter=popular',
                'description'    => 'These combinations have been ordered multiple times by our customers. Try them yourself or use them as inspiration for your own creation.',
                'status'         => '1',
                'bg_color'       => '#ffffff',
                'text_color'     => '#1f2937',
                'position'       => '3',
                'language_id'    => $language->id,
                'type'           => PageType::HOME,
            ],
            [
                'id'             => 3,
                'title'          => 'Create Your Own',
                'featured_title' => 'Compose Your Perfect Blend',
                'subtitle'       => 'Full control over every ingredient',
                'label'          => 'Start Composing',
                'link'           => '#compose',
                'description'    => 'Want something completely unique? Use our composition tool to build your perfect blend from scratch with premium ingredients.',
                'status'         => '1',
                'bg_color'       => '#fef3e2',
                'text_color'     => '#1f2937',
                'position'       => '4',
                'language_id'    => $language->id,
                'type'           => PageType::POPULAR_COMBINATIONS,
            ],
        ];

        foreach ($sections as $sectionData) {
            Section::firstOrCreate(['id' => $sectionData['id']], $sectionData);
        }

    }
}
