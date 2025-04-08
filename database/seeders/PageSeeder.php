<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Create a default homepage
        // Page::create([
        //     'title' => 'Welcome to Our Website',
        //     'slug' => 'home',
        //     'description' => '<div class="container mx-auto px-4 py-12">
        //         <h1 class="text-4xl font-bold text-center mb-8">Welcome to ' . settings('site_title', 'RestoPos') . '</h1>
        //         <div class="prose lg:prose-xl mx-auto">
        //             <p>This is the default homepage content. You can edit this in the admin panel.</p>
        //         </div>
        //     </div>',
        //     'meta_title' => settings('site_title', 'RestoPos'),
        //     'meta_description' => settings('site_description', 'Welcome to our website'),
        //     'is_homepage' => true,
        //     'status' => true,
        // ]);
        
        // Create additional pages
        Page::factory(5)->create();
    }
}
