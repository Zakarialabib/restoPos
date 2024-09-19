<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.guest')]
#[Title('Menu')]
class Index extends Component
{
    use WithPagination;


    public $currentTheme = 'sunset';
    public $themes = [
        'earth' => [
            'bg' => 'bg-[#FFFBEB]',
            'text' => 'text-[#292524]',
            'accent' => 'text-[#059669]',
            'card' => [
                'bg' => 'bg-white bg-opacity-80 backdrop-filter backdrop-blur-lg',
                'button' => 'bg-[#E07A5F] hover:bg-[#C65D45] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#292524]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#4A6670]',
            ],
            'header' => [
                'bg' => 'bg-[#6B9080]',
                'text' => 'text-white',
            ],
        ],
        'pastel' => [
            'bg' => 'bg-[#FFF1F2]',
            'text' => 'text-[#334155]',
            'accent' => 'text-[#38BDF8]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#C4B5FD] hover:bg-[#A78BFA] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#334155]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#34D399]',
            ],
            'header' => [
                'bg' => 'bg-[#86EFAC]',
                'text' => 'text-white',
            ],
        ],
        'vibrant' => [
            'bg' => 'bg-[#FACC15]',
            'text' => 'text-[#581C87]',
            'accent' => 'text-[#EC4899]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#EC4899] hover:bg-[#DB2777] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#581C87]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#7E22CE]',
            ],
            'header' => [
                'bg' => 'bg-[#7C3AED]',
                'text' => 'text-white',
            ],
        ],
        'monochrome' => [
            'bg' => 'bg-[#F3F4F6]',
            'text' => 'text-[#111827]',
            'accent' => 'text-[#4B5563]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#1F2937] hover:bg-[#111827] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#111827]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#4B5563]',
            ],
            'header' => [
                'bg' => 'bg-[#374151]',
                'text' => 'text-white',
            ],
        ],
        'dark' => [
            'bg' => 'bg-[#111827]',
            'text' => 'text-[#F3F4F6]',
            'accent' => 'text-[#34D399]',
            'card' => [
                'bg' => 'bg-[#1F2937]',
                'button' => 'bg-[#10B981] hover:bg-[#059669] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#34D399]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#D1D5DB]',
            ],
            'header' => [
                'bg' => 'bg-[#1F2937]',
                'text' => 'text-[#34D399]',
            ],
        ],
        'ocean' => [
            'bg' => 'bg-[#E0F2FE]',
            'text' => 'text-[#0C4A6E]',
            'accent' => 'text-[#0891B2]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#0EA5E9] hover:bg-[#0284C7] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#0C4A6E]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#0369A1]',
            ],
            'header' => [
                'bg' => 'bg-[#0284C7]',
                'text' => 'text-white',
            ],
        ],
        'forest' => [
            'bg' => 'bg-[#ECFDF5]',
            'text' => 'text-[#064E3B]',
            'accent' => 'text-[#059669]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#10B981] hover:bg-[#059669] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#064E3B]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#047857]',
            ],
            'header' => [
                'bg' => 'bg-[#059669]',
                'text' => 'text-white',
            ],
        ],
        'sunset' => [
            'bg' => 'bg-[#FFEDD5]',
            'text' => 'text-[#7C2D12]',
            'accent' => 'text-[#EA580C]',
            'card' => [
                'bg' => 'bg-white',
                'button' => 'bg-[#EA580C] hover:bg-[#C2410C] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#7C2D12]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#9A3412]',
            ],
            'header' => [
                'bg' => 'bg-[#C2410C]',
                'text' => 'text-white',
            ],
        ],
        'neon' => [
            'bg' => 'bg-[#0F172A]',
            'text' => 'text-[#F472B6]',
            'accent' => 'text-[#3B82F6]',
            'card' => [
                'bg' => 'bg-[#1E293B]',
                'button' => 'bg-[#3B82F6] hover:bg-[#2563EB] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#F472B6]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#818CF8]',
            ],
            'header' => [
                'bg' => 'bg-[#2563EB]',
                'text' => 'text-white',
            ],
        ],
        'elegant' => [
            'bg' => 'bg-[#F0EAE2]',
            'text' => 'text-[#3D3D3D]',
            'accent' => 'text-[#8E7F7F]',
            'card' => [
                'bg' => 'bg-white bg-opacity-80 backdrop-filter backdrop-blur-lg',
                'button' => 'bg-[#8E7F7F] hover:bg-[#6D5D5D] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#3D3D3D]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#5A5A5A]',
            ],
            'header' => [
                'bg' => 'bg-[#8E7F7F]',
                'text' => 'text-white',
            ],
        ],
        'rustic' => [
            'bg' => 'bg-[#F5E6D3]',
            'text' => 'text-[#4A3933]',
            'accent' => 'text-[#A67C52]',
            'card' => [
                'bg' => 'bg-[#FFFFFF] bg-opacity-70',
                'button' => 'bg-[#A67C52] hover:bg-[#8C6642] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#4A3933]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#6B5B54]',
            ],
            'header' => [
                'bg' => 'bg-[#A67C52]',
                'text' => 'text-white',
            ],
        ],
        'fresh' => [
            'bg' => 'bg-[#E8F3E8]',
            'text' => 'text-[#2C5F2D]',
            'accent' => 'text-[#97BC62]',
            'card' => [
                'bg' => 'bg-white bg-opacity-80',
                'button' => 'bg-[#97BC62] hover:bg-[#7FA650] text-retro-orange',
                'product_name' => 'text-lg lg:text-xl font-semibold text-[#2C5F2D]',
                'product_price' => 'text-4xl font-semibold text-black',
                'product_description' => 'text-sm lg:text-base opacity-75 leading-relaxed text-[#4A7B4B]',
            ],
            'header' => [
                'bg' => 'bg-[#97BC62]',
                'text' => 'text-white',
            ],
        ],
    ];

    public $isOpen = false;
    public $search = '';
    public $activeCategory = 'all';
    public $category_id = '';

    #[Computed]
    public function products()
    {
        return Product::query()
            ->where('is_available', true)
            ->when($this->search, function ($query): void {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_id, function ($query): void {
                $query->where('category_id', $this->category_id);
            })
            ->get();
    }

    #[Computed]
    public function categories()
    {
        return Category::query()->get();
    }

    public function getTheme($key)
    {
        return $this->themes[$this->currentTheme][$key];
    }

    public function changeTheme($theme): void
    {
        $this->currentTheme = $theme;
    }

    public function togglePanel(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function render()
    {
        return view('livewire.index');
    }
}
