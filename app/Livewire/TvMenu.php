<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Services\MenuService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

#[Layout('layouts.tv-menu')]
#[Title('Digital Menu Display')]
class TvMenu extends Component
{
    // Display mode options
    public const DISPLAY_MODES = [
        'GRID_VIEW' => [
            'id' => 'GRID_VIEW',
            'label' => 'Grid View',
            'icon' => 'layout-grid', // Use Lucide icon names or similar
            'config' => [
                'showPrices' => true,
                'showDescription' => false, // Default to hide description in grid
                'showNavigation' => true,
                'autoplaySpeed' => 0, // Typically no autoplay for grids you interact with
                'styleVariant' => 'tvMinimal', // Can be overridden by uiConfig
                'slidesPerView' => 3, // Default, maybe make responsive?
                'slidesPerGroup' => 3,
                'spaceBetween' => 30,
                'direction' => 'horizontal',
                'effect' => 'slide', // Standard slide effect
                'loop' => true,
            ]
        ],
        'SHOWCASE' => [
            'id' => 'SHOWCASE',
            'label' => 'Showcase',
            'icon' => 'maximize-2',
            'config' => [
                'showPrices' => true,
                'showDescription' => true, // Show more detail here
                'showNavigation' => false, // Usually controlled by autoplay
                'autoplaySpeed' => 8000, // Autoplay makes sense here
                'styleVariant' => 'tv',
                'slidesPerView' => 1,
                'slidesPerGroup' => 1,
                'spaceBetween' => 0,
                'direction' => 'horizontal',
                'effect' => 'fade', // Fade or Creative effect works well
                'creativeEffect' => [ // Example if using creative
                    'prev' => ['shadow' => true, 'translate' => ['-100%', 0, -500], 'opacity' => 0],
                    'next' => ['shadow' => true, 'translate' => ['100%', 0, -500], 'opacity' => 0],
                ],
                'loop' => true,
                // Add config for Ken Burns? e.g., 'kenBurns' => true
            ]
        ],
        'LIST_VIEW' => [
            'id' => 'LIST_VIEW',
            'label' => 'List View',
            'icon' => 'rows', // Or 'list'
            'config' => [
                'showPrices' => true,
                'showDescription' => true,
                'showNavigation' => true,
                'autoplaySpeed' => 0, // Or timed scroll?
                'styleVariant' => 'tvMinimal',
                'slidesPerView' => 5, // Show more items vertically
                'slidesPerGroup' => 5,
                'spaceBetween' => 10, // Less space usually needed for lists
                'direction' => 'vertical', // Key difference
                'effect' => 'slide',
                'loop' => false, // Loop might feel weird vertically
            ]
        ],
        'DYNAMIC_VIEW' => [
            'id' => 'DYNAMIC_VIEW',
            'label' => 'Dynamic View',
            'icon' => 'star', // Or 'layout-dashboard'
            'config' => [
                'showPrices' => true,
                'showDescription' => true,
                'showNavigation' => true,
                'autoplaySpeed' => 7000,
                'styleVariant' => 'tv',
                'slidesPerView' => 'auto', // Key for dynamic widths
                'slidesPerGroup' => 1, // Slide one by one usually makes sense
                'spaceBetween' => 20,
                'direction' => 'horizontal',
                'effect' => 'slide',
                'centeredSlides' => false, // Usually not centered with 'auto'
                'loop' => true,
            ]
        ],
        'STATIC_COLUMNS' => [ // New Mode
            'id' => 'STATIC_COLUMNS',
            'label' => 'Static Columns',
            'icon' => 'columns', // Or 'newspaper'
            'config' => [
                'showPrices' => true,
                'showDescription' => true,
                'showNavigation' => false, // No Swiper navigation
                'autoplaySpeed' => 0, // No autoplay
                'styleVariant' => 'paper', // Specific variant
                // Config specific to this mode can go in uiConfig
                // e.g., uiConfig['staticColumnsCount'] = 2
            ]
        ]
    ];

    // Theme options
    public const THEMES = [
        'default' => [
            'name' => 'Default',
            'primary' => '#f97316',
            'secondary' => '#1e293b',
            'background' => '#121212',
            'text' => '#ffffff',
            'accent' => '#f97316'
        ],
        'dark' => [
            'name' => 'Dark',
            'primary' => '#0f172a',
            'secondary' => '#1e293b',
            'background' => '#020617',
            'text' => '#f8fafc',
            'accent' => '#3b82f6'
        ],
        'light' => [
            'name' => 'Light',
            'primary' => '#f8fafc',
            'secondary' => '#e2e8f0',
            'background' => '#ffffff',
            'text' => '#0f172a',
            'accent' => '#3b82f6'
        ],
        'vintage' => [
            'name' => 'Vintage',
            'primary' => '#854d0e',
            'secondary' => '#422006',
            'background' => '#fef3c7',
            'text' => '#422006',
            'accent' => '#854d0e'
        ],
        'modern' => [
            'name' => 'Modern',
            'primary' => '#0f172a',
            'secondary' => '#1e293b',
            'background' => '#f8fafc',
            'text' => '#0f172a',
            'accent' => '#3b82f6'
        ]
    ];

    // Component state properties
    public string $displayMode = 'SHOWCASE';
    public string $currentTheme = 'default';
    public bool $isMuted = true;
    public bool $autoRotate = false;
    public int $rotationInterval = 10000; // 10 seconds
    public ?int $currentCategory = null;
    public int $currentSlideIndex = 0;
    public bool $isFullScreen = false;
    public bool $showSettings = false;
    public $currentTime;
    public $themeConfig = [
        'name' => 'default',
        'primary' => '#f97316',
        'secondary' => '#1e293b',
        'background' => '#121212',
        'text' => '#ffffff',
        'accent' => '#f97316'
    ];

    // UI configuration
    public array $uiConfig = [
        'slidesPerView' => [
            'grid' => 3,
            'list' => 4,
            'showcase' => 1,
            'dynamic' => 2
        ],
        'spaceBetween' => 30,
        'enablePagination' => true,
        'enableNavigation' => true,
        'transitionSpeed' => 500,
        'backgroundOpacity' => 0.95,
        'showClock' => true,
        'showLogo' => true,
        'clockFormat' => '24h',
        'showTemperature' => true,
        'logoSize' => 'large',
        'backgroundEffect' => 'gradient',
        'fontScale' => 1.2,
        'animationSpeed' => 'normal',
        'showPrices' => true,
        'showDescription' => true,
    ];

    // Polling for real-time updates
    public function getListeners()
    {
        return [
            'echo:menu-updates,MenuUpdated' => 'refreshMenu',
            'changeDisplayMode' => 'setDisplayMode',
            'changeTheme' => 'setTheme',
            'toggleAutoRotate' => 'toggleAutoRotate',
            'toggleMute' => 'toggleMute',
            'setCategory' => 'setCategory',
            'toggleSettings' => 'toggleSettings',
            'updateUiConfig' => 'updateUiConfig',
        ];
    }

    public function mount(): void
    {
        $this->updateCurrentTime();
    }

    public function updateCurrentTime()
    {
        $this->currentTime = Carbon::now()->format('H:i');
    }

    public function refreshMenu(): void
    {
        // This will be called when menu data changes
        $this->dispatch('refresh');
    }

    public function setDisplayMode(string $mode): void
    {
        if (array_key_exists($mode, self::DISPLAY_MODES)) {
            $this->displayMode = $mode;
            $this->dispatch('displayModeChanged', mode: $mode);
        }
    }

    public function setTheme(string $theme): void
    {
        if (array_key_exists($theme, self::THEMES)) {
            $this->themeConfig = self::THEMES[$theme];
            $this->dispatch('themeChanged');
        }
    }

    public function toggleAutoRotate(): void
    {
        $this->autoRotate = ! $this->autoRotate;
    }

    public function toggleMute(): void
    {
        $this->isMuted = ! $this->isMuted;
    }

    public function setCategory(?int $categoryId): void
    {
        $this->currentCategory = $categoryId;
    }

    public function toggleSettings(): void
    {
        $this->showSettings = ! $this->showSettings;
    }

    public function updateUiConfig(array $config): void
    {
        $this->uiConfig = array_merge($this->uiConfig, $config);
    }

    public function getCurrentTime()
    {
        $now = now();

        if ('24h' === $this->uiConfig['clockFormat']) {
            return $now->format('H:i');
        }

        return $now->format('g:i A');
    }

    public function getCurrentDisplayModeProperty()
    {
        return self::DISPLAY_MODES[$this->displayMode];
    }

    public function getDisplayModesProperty()
    {
        return self::DISPLAY_MODES;
    }

    public function getThemesProperty()
    {
        return self::THEMES;
    }

    public function getOrganizedProductsProperty()
    {
        return Cache::remember('tv_menu_products', 3600, function () {
            return [
                'all' => Product::with('category')
                    ->where('is_active', true)
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('name')
                    ->get(),
                'categories' => Category::with(['products' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('is_featured', 'desc')
                        ->orderBy('name');
                }])->whereHas('products', function ($query) {
                    $query->where('is_active', true);
                })->get()
            ];
        });
    }

    public function render(MenuService $menuService)
    {
        $products = $menuService->getActiveProducts();
        $categories = Category::all();

        // Organize products for different display modes
        $organizedProducts = [
            'all' => $products,
            'featured' => $products->filter(fn($p) => $p->is_featured),
            'specials' => $products->filter(fn($p) => $p->is_special),
            'popular' => $products->filter(fn($p) => $p->is_popular),
            'new' => $products->filter(fn($p) => $p->is_new),
            'byCategory' => $categories->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'products' => $products->filter(fn($p) => $p->category_id === $cat->id)
            ])
        ];

        // Get current display mode configuration
        $currentDisplayMode = self::DISPLAY_MODES[$this->displayMode] ?? self::DISPLAY_MODES['SHOWCASE'];

        // Get current theme configuration
        $currentThemeConfig = self::THEMES[$this->currentTheme] ?? self::THEMES['default'];

        return view('livewire.tv-menu', [
            'products' => $products,
            'categories' => $categories,
            'organizedProducts' => $organizedProducts,
            'currentDisplayMode' => $currentDisplayMode,
            'displayModes' => self::DISPLAY_MODES,
            'themeConfig' => $currentThemeConfig,
            'themes' => self::THEMES,
            'currentTime' => $this->currentTime,
        ]);
    }
}
