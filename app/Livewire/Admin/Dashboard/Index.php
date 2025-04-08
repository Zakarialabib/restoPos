<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dashboard;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\IngredientStockService;
use App\Services\PredictionService;
use App\Services\ProductService;
use App\Services\ReportingService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Dashboard')]
#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public int $totalProducts;
    public $recentOrders;
    public Carbon $startDate;
    public Carbon $endDate;
    public string $search = '';
    public string $dateRange = 'last_30_days';
    public ?string $customStartDate = null;
    public ?string $customEndDate = null;
    public string $reportType = 'sales';
    public int $refreshInterval = 300; // 5 minutes in seconds
    public array $selectedMetrics = ['revenue', 'orders', 'customers'];
    public Collection $lowStockProducts;
    public Collection $topSellingCategories;
    public array $chartData = [];

    public $activeTab = 'overview';
    public $searchQuery = '';
    public $filters = [];

    #[Validate('string|nullable')]

    public $category_filter = '';

    // selectedItem
    public $selectedItem = null;

    protected ReportingService $reportingService;
    protected PredictionService $predictiveService;
    protected IngredientStockService $stockService;
    protected ProductService $productService;

    protected $listeners = ['refreshDashboard' => '$refresh'];
    protected $queryString = [
        'activeTab' => ['except' => 'overview'],
        'searchQuery' => ['except' => '']
    ];


    #[Computed]
    public function stockAlerts()
    {
        return $this->stockService->getStockAlerts($this->category_filter);
    }

    public function getTabData()
    {
        return match ($this->activeTab) {
            'overview' => $this->getOverviewData(),
            'products' => $this->getProductsData(),
            'inventory' => $this->getInventoryData(),
            'orders' => $this->getOrdersData(),
            default => []
        };
    }

    public function boot(ReportingService $reportingService, PredictionService $predictiveService, IngredientStockService $stockService, ProductService $productService): void
    {
        $this->stockService = $stockService;
        $this->reportingService = $reportingService;
        $this->predictiveService = $predictiveService;
        $this->productService = $productService;
    }

    public function mount(): void
    {
        $this->totalProducts = Product::where('status', true)->count();
        $this->recentOrders = Order::with(['items.product', 'user'])
            ->latest()
            ->take(5)
            ->get();
        $this->setDateRangeFromPreset($this->dateRange);
        $this->loadLowStockProducts();
        $this->loadChartData();
    }

    #[Computed(seconds: 300)]
    public function topSellingProducts(): array
    {
        return OrderItem::with('product.category')
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get()
            ->map(fn ($item) => [
                'name' => $item->product->name,
                'category' => $item->product->category->name,
                'quantity' => $item->total_quantity,
                'revenue' => $item->total_revenue,
            ])
            ->toArray();
    }
    #[Computed]
    public function categories()
    {
        return Category::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }
    #[Computed(seconds: 300)]
    public function categoryPerformance(): array
    {
        return OrderItem::with('product.category')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get()
            ->toArray();
    }

    public function setDateRangeFromPreset(string $preset): void
    {
        match ($preset) {
            'today' => $this->setDateRange(Carbon::today(), Carbon::today()),
            'this_week' => $this->setDateRange(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()),
            'this_month' => $this->setDateRange(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()),
            'this_year' => $this->setDateRange(Carbon::now()->startOfYear(), Carbon::now()->endOfYear()),
            default => $this->setDateRange(Carbon::now()->subDays(30), Carbon::now()),
        };
    }

    public function setDateRange(Carbon $startDate, Carbon $endDate): void
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    #[Computed(seconds: 300)]
    public function totalRevenue()
    {
        return Order::whereBetween('created_at', [$this->startDate, $this->endDate])->sum('total_amount');
    }

    #[Computed(seconds: 300)]
    public function totalOrders(): int
    {
        return Order::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
    }

    #[Computed(seconds: 300)]
    public function productAnalytics(): array
    {
        return $this->productService->getProductAnalytics($this->startDate, $this->endDate);
    }

    #[Computed(seconds: 300)]
    public function averageOrderValue(): float
    {
        return $this->totalOrders() > 0
            ? $this->totalRevenue() / $this->totalOrders()
            : 0;
    }

    public function render()
    {
        return view('livewire.admin.dashboard.index', [
            'lowStockProductsList' => $this->lowStockProducts,
            'chartData' => $this->chartData,
        ]);
    }

    private function loadChartData(): void
    {
        $this->chartData = [
            'revenue' => $this->reportingService->getRevenueData($this->startDate, $this->endDate),
            'predictions' => $this->predictiveService->getPredictedDemand($this->startDate, $this->endDate)
        ];
    }

    private function loadLowStockProducts(): void
    {
        $this->lowStockProducts = Product::with('category')
            ->where('stock_quantity', '<=', DB::raw('reorder_point'))
            ->where('status', true)
            ->take(5)
            ->get();
    }

    private function getOverviewData(): array
    {
        return [
            'totalRevenue' => $this->totalRevenue(),
            'totalOrders' => $this->totalOrders(),
            'averageOrderValue' => $this->averageOrderValue(),
        ];
    }

    private function getProductsData(): array
    {
        return [
            'topSellingProducts' => $this->topSellingProducts(),
            'lowStockProducts' => $this->lowStockProducts,
        ];
    }

    private function getInventoryData(): array
    {
        return [
            'lowStockProducts' => $this->lowStockProducts,
        ];
    }

    private function getOrdersData(): array
    {
        return [
            'recentOrders' => $this->recentOrders,
        ];
    }
}
