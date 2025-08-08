# RestoPos Technical Developer Guide

This guide provides in-depth technical information for developers working on RestoPos, following our 100% native Laravel/Livewire philosophy.

## 🎯 Development Philosophy

**Keep it 100% native Laravel/Livewire.**

No extra layers, no DTOs, no gateways, no CQRS, no design-pattern bingo. The framework already gives us Models, Form Requests, Jobs, Actions, Livewire components—let's just use them well.

## 📁 Project Structure

### Core Directories
```
app/
├─ Models/                 # Eloquent models only
├─ Livewire/               # All components (full-page and partial)
│  ├─ Admin/               # Admin panel components
│  ├─ Customer/            # Customer portal components
│  ├─ Kitchen/             # Kitchen display components
│  ├─ Tv/                  # TV display components
│  └─ Pos/                 # POS system components
├─ Actions/                # Single-purpose invokable classes
├─ Policies/               # Authorization policies
├─ Observers/              # Eloquent model observers
├─ Jobs/                   # Queued background jobs
├─ Mail/                   # Email templates and mailables
└─ Exceptions/             # Custom application exceptions

resources/
├─ views/
│  ├─ livewire/            # Livewire component views
│  ├─ layouts/             # Application layouts
│  └─ components/          # Blade components
└─ js/                     # Frontend assets (Alpine.js, custom JS)

routes/
├─ web.php                 # Livewire full-page components
└─ api.php                 # API endpoints (minimal usage)

database/
├─ migrations/             # Database schema changes
├─ factories/              # Model factories for testing
└─ seeders/                # Database seeders
```

### Naming Conventions

- **Namespaces**: CamelCase, singular (`App\Models\User`)
- **Tables**: snake_case, plural (`users`, `order_items`)
- **Columns**: snake_case (`created_at`, `user_id`)
- **Components**: StudlyCase (`UserProfile.php`)
- **Component Usage**: kebab-case (`<livewire:user-profile />`)
- **Actions**: PascalCase with Action suffix (`CreateOrderAction`)

## 🔧 Core Components Architecture

### Livewire as Controllers

**Never create classic controllers.** Create Livewire components for each screen or widget.

```php
<?php

namespace App\Livewire\Admin\Orders;

use App\Actions\CreateOrderAction;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public array $selectedOrders = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updateOrderStatus(int $orderId, string $status)
    {
        $order = Order::findOrFail($orderId);
        $this->authorize('update', $order);
        
        $order->update(['status' => $status]);
        
        $this->dispatch('order-updated', orderId: $orderId);
    }

    public function render()
    {
        return view('livewire.admin.orders.order-management', [
            'orders' => $this->getOrders(),
        ]);
    }

    private function getOrders()
    {
        return Order::query()
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->status !== 'all', fn($q) => $q->where('status', $this->status))
            ->with(['customer', 'items.product'])
            ->latest()
            ->paginate(15);
    }
}
```

### Actions Pattern

Actions are single-purpose invokable classes that handle business logic:

```php
<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

final class CreateOrderAction
{
    public function __invoke(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'table_id' => $data['table_id'] ?? null,
                'type' => $data['type'], // dine_in, takeaway, delivery
                'status' => 'pending',
                'subtotal' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'notes' => $data['notes'] ?? null,
            ]);

            $subtotal = 0;
            
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemTotal = $product->price * $item['quantity'];
                
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $itemTotal,
                    'customizations' => $item['customizations'] ?? [],
                ]);
                
                $subtotal += $itemTotal;
            }
            
            $taxAmount = $subtotal * 0.1; // 10% tax
            $total = $subtotal + $taxAmount;
            
            $order->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $total,
            ]);
            
            return $order->load('items.product');
        });
    }
}
```

## 🏗️ System Components

### 1. Customer Portal

**Purpose**: Mobile-optimized ordering interface accessed via QR codes

**Key Components**:
- `App\Livewire\Customer\MenuBrowser`
- `App\Livewire\Customer\ProductCustomizer`
- `App\Livewire\Customer\OrderCart`
- `App\Livewire\Customer\OrderCheckout`

**Technical Implementation**:
```php
<?php

namespace App\Livewire\Customer;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;

class MenuBrowser extends Component
{
    public ?int $selectedCategory = null;
    public string $search = '';
    public string $tableCode = '';
    
    public function mount(string $table = null)
    {
        $this->tableCode = $table ?? '';
    }
    
    public function selectCategory(?int $categoryId)
    {
        $this->selectedCategory = $categoryId;
    }
    
    public function addToCart(int $productId)
    {
        $this->dispatch('add-to-cart', productId: $productId);
    }
    
    public function render()
    {
        return view('livewire.customer.menu-browser', [
            'categories' => Category::active()->orderBy('sort_order')->get(),
            'products' => $this->getProducts(),
        ])->layout('layouts.customer');
    }
    
    private function getProducts()
    {
        return Product::query()
            ->active()
            ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
            ->when($this->search, fn($q) => $q->search($this->search))
            ->with(['category', 'media'])
            ->orderBy('sort_order')
            ->get();
    }
}
```

### 2. TV Display System

**Purpose**: Non-interactive display for order status and menu information

**Key Components**:
- `App\Livewire\Tv\OrderDisplay`
- `App\Livewire\Tv\MenuDisplay`
- `App\Livewire\Tv\PromotionDisplay`

**Real-time Updates**:
```php
<?php

namespace App\Livewire\Tv;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\On;

class OrderDisplay extends Component
{
    public string $displayMode = 'queue'; // queue, ready, completed
    public int $refreshInterval = 30; // seconds
    
    #[On('order-updated')]
    public function refreshOrders()
    {
        // Component will re-render automatically
    }
    
    public function render()
    {
        return view('livewire.tv.order-display', [
            'orders' => $this->getOrdersByMode(),
        ])->layout('layouts.tv');
    }
    
    private function getOrdersByMode()
    {
        return match($this->displayMode) {
            'queue' => Order::pending()->with(['items.product', 'table'])->latest()->take(10)->get(),
            'ready' => Order::ready()->with(['items.product', 'table'])->latest()->take(8)->get(),
            'completed' => Order::completed()->with(['items.product', 'table'])->latest()->take(6)->get(),
            default => collect(),
        };
    }
}
```

### 3. Composable Products System

**Purpose**: Allow customers to customize products with ingredients

**Key Models**:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'category_id',
        'is_composable', 'is_active', 'sort_order'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'is_composable' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    public function ingredientGroups(): BelongsToMany
    {
        return $this->belongsToMany(IngredientGroup::class)
            ->withPivot(['is_required', 'min_selections', 'max_selections'])
            ->orderBy('sort_order');
    }
    
    public function calculateCustomPrice(array $selectedIngredients): float
    {
        $basePrice = $this->price;
        $additionalCost = 0;
        
        foreach ($selectedIngredients as $ingredientId => $quantity) {
            $ingredient = Ingredient::find($ingredientId);
            if ($ingredient) {
                $additionalCost += $ingredient->additional_cost * $quantity;
            }
        }
        
        return $basePrice + $additionalCost;
    }
}

class IngredientGroup extends Model
{
    protected $fillable = [
        'name', 'description', 'selection_type', 'sort_order'
    ];
    
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class)->orderBy('sort_order');
    }
}

class Ingredient extends Model
{
    protected $fillable = [
        'name', 'description', 'additional_cost', 'ingredient_group_id',
        'is_available', 'allergens', 'nutritional_info'
    ];
    
    protected $casts = [
        'additional_cost' => 'decimal:2',
        'is_available' => 'boolean',
        'allergens' => 'array',
        'nutritional_info' => 'array',
    ];
}
```

**Customization Component**:
```php
<?php

namespace App\Livewire\Customer;

use App\Models\Product;
use App\Models\Ingredient;
use Livewire\Component;

class ProductCustomizer extends Component
{
    public Product $product;
    public array $selectedIngredients = [];
    public float $currentPrice;
    public array $nutritionalInfo = [];
    
    public function mount(Product $product)
    {
        $this->product = $product->load('ingredientGroups.ingredients');
        $this->currentPrice = $product->price;
    }
    
    public function updatedSelectedIngredients()
    {
        $this->currentPrice = $this->product->calculateCustomPrice($this->selectedIngredients);
        $this->calculateNutritionalInfo();
    }
    
    public function addToCart()
    {
        $this->validate([
            'selectedIngredients' => 'required|array',
        ]);
        
        $this->dispatch('add-customized-product', [
            'productId' => $this->product->id,
            'customizations' => $this->selectedIngredients,
            'customPrice' => $this->currentPrice,
        ]);
    }
    
    public function render()
    {
        return view('livewire.customer.product-customizer');
    }
    
    private function calculateNutritionalInfo()
    {
        $nutrition = ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0];
        
        foreach ($this->selectedIngredients as $ingredientId => $quantity) {
            $ingredient = Ingredient::find($ingredientId);
            if ($ingredient && $ingredient->nutritional_info) {
                foreach ($nutrition as $key => $value) {
                    $nutrition[$key] += ($ingredient->nutritional_info[$key] ?? 0) * $quantity;
                }
            }
        }
        
        $this->nutritionalInfo = $nutrition;
    }
}
```

### 4. Kitchen Display System

**Purpose**: Real-time order management for kitchen staff

**Key Components**:
```php
<?php

namespace App\Livewire\Kitchen;

use App\Models\Order;
use App\Actions\UpdateOrderStatusAction;
use Livewire\Component;
use Livewire\Attributes\On;

class KitchenDisplay extends Component
{
    public string $station = 'all'; // all, grill, salad, drinks
    public array $orderStatuses = ['pending', 'preparing', 'ready'];
    
    #[On('new-order')]
    public function handleNewOrder()
    {
        $this->dispatch('play-notification-sound');
    }
    
    public function updateOrderStatus(int $orderId, string $status, UpdateOrderStatusAction $updateStatus)
    {
        $order = Order::findOrFail($orderId);
        $this->authorize('update', $order);
        
        $updateStatus(['order_id' => $orderId, 'status' => $status]);
        
        $this->dispatch('order-updated', orderId: $orderId);
    }
    
    public function render()
    {
        return view('livewire.kitchen.kitchen-display', [
            'orders' => $this->getKitchenOrders(),
        ])->layout('layouts.kitchen');
    }
    
    private function getKitchenOrders()
    {
        return Order::query()
            ->whereIn('status', $this->orderStatuses)
            ->with(['items.product', 'table', 'customer'])
            ->when($this->station !== 'all', function($q) {
                $q->whereHas('items.product', fn($q) => $q->where('station', $this->station));
            })
            ->orderBy('created_at')
            ->get()
            ->groupBy('status');
    }
}
```

## 🔐 Authentication & Authorization

### Multi-Guard Authentication

```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'customer' => [
        'driver' => 'session',
        'provider' => 'customers',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'customers' => [
        'driver' => 'eloquent',
        'model' => App\Models\Customer::class,
    ],
],
```

### Policy-Based Authorization

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('orders.view');
    }
    
    public function view(User $user, Order $order): bool
    {
        return $user->hasPermission('orders.view') || $order->created_by === $user->id;
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('orders.create');
    }
    
    public function update(User $user, Order $order): bool
    {
        return $user->hasPermission('orders.update') && 
               ($order->status === 'pending' || $user->hasRole('manager'));
    }
    
    public function delete(User $user, Order $order): bool
    {
        return $user->hasPermission('orders.delete') && 
               $order->status === 'pending';
    }
}
```

## 📊 Real-time Features

### Broadcasting Events

```php
<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public function __construct(
        public Order $order,
        public string $previousStatus
    ) {}
    
    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
            new Channel('kitchen'),
            new Channel('tv-display'),
        ];
    }
    
    public function broadcastWith(): array
    {
        return [
            'order' => $this->order->load('items.product', 'table'),
            'previous_status' => $this->previousStatus,
            'timestamp' => now()->toISOString(),
        ];
    }
}
```

### Livewire Real-time Integration

```php
// In Livewire components
#[On('echo:orders,OrderStatusUpdated')]
public function handleOrderUpdate($event)
{
    // Component automatically re-renders
    $this->dispatch('order-status-changed', $event['order']['id']);
}
```

## 🧪 Testing Strategy

### Feature Tests

```php
<?php

namespace Tests\Feature\Livewire\Customer;

use App\Livewire\Customer\MenuBrowser;
use App\Models\Product;
use App\Models\Category;
use Livewire\Livewire;
use Tests\TestCase;

class MenuBrowserTest extends TestCase
{
    public function test_can_browse_menu_by_category()
    {
        $category = Category::factory()->create(['name' => 'Beverages']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Coffee',
        ]);
        
        Livewire::test(MenuBrowser::class)
            ->call('selectCategory', $category->id)
            ->assertSee('Coffee')
            ->assertSet('selectedCategory', $category->id);
    }
    
    public function test_can_search_products()
    {
        Product::factory()->create(['name' => 'Espresso']);
        Product::factory()->create(['name' => 'Cappuccino']);
        Product::factory()->create(['name' => 'Orange Juice']);
        
        Livewire::test(MenuBrowser::class)
            ->set('search', 'coffee')
            ->assertSee('Espresso')
            ->assertSee('Cappuccino')
            ->assertDontSee('Orange Juice');
    }
}
```

### Action Tests

```php
<?php

namespace Tests\Unit\Actions;

use App\Actions\CreateOrderAction;
use App\Models\Product;
use App\Models\Customer;
use Tests\TestCase;

class CreateOrderActionTest extends TestCase
{
    public function test_creates_order_with_items()
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['price' => 10.00]);
        
        $action = new CreateOrderAction();
        
        $order = $action([
            'customer_id' => $customer->id,
            'type' => 'dine_in',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ]);
        
        $this->assertEquals(20.00, $order->subtotal);
        $this->assertEquals(2.00, $order->tax_amount);
        $this->assertEquals(22.00, $order->total_amount);
        $this->assertCount(1, $order->items);
    }
}
```

## 🚀 Performance Optimization

### Database Optimization

```php
// Eager loading relationships
public function getOrders()
{
    return Order::with([
        'customer:id,name,email',
        'items:id,order_id,product_id,quantity,unit_price',
        'items.product:id,name,price',
        'table:id,number'
    ])->latest()->paginate(15);
}

// Query scopes for common filters
class Order extends Model
{
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
    
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('id', 'like', "%{$term}%")
              ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$term}%"));
        });
    }
}
```

### Caching Strategy

```php
// Cache frequently accessed data
class MenuService
{
    public function getActiveCategories()
    {
        return Cache::remember('categories.active', 3600, function() {
            return Category::active()
                ->with('products')
                ->orderBy('sort_order')
                ->get();
        });
    }
    
    public function clearMenuCache()
    {
        Cache::forget('categories.active');
        Cache::forget('products.featured');
    }
}

// Observer to clear cache when data changes
class CategoryObserver
{
    public function saved(Category $category)
    {
        app(MenuService::class)->clearMenuCache();
    }
}
```

## 🔧 Development Tools

### Laravel Telescope
Monitor queries, jobs, and performance:
```bash
php artisan telescope:install
php artisan migrate
```

### Code Quality Tools

```bash
# PHP CS Fixer
vendor/bin/php-cs-fixer fix

# PHPStan
vendor/bin/phpstan analyse

# Pest Testing
vendor/bin/pest

# Livewire Testing
vendor/bin/pest --filter=Livewire
```

### Debugging

```php
// Livewire debugging
class OrderManagement extends Component
{
    public function render()
    {
        // Debug component state
        if (app()->environment('local')) {
            logger('OrderManagement state', [
                'search' => $this->search,
                'status' => $this->status,
                'selected' => $this->selectedOrders,
            ]);
        }
        
        return view('livewire.admin.orders.order-management');
    }
}
```

## 📱 Mobile Optimization

### Responsive Design

```php
// Mobile-first Livewire components
class MobileMenuBrowser extends Component
{
    public bool $showFilters = false;
    public bool $showCart = false;
    
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }
    
    public function toggleCart()
    {
        $this->showCart = !$this->showCart;
    }
    
    public function render()
    {
        return view('livewire.customer.mobile-menu-browser')
            ->layout('layouts.mobile');
    }
}
```

### PWA Features

```javascript
// Service Worker for offline functionality
self.addEventListener('fetch', event => {
    if (event.request.url.includes('/api/menu')) {
        event.respondWith(
            caches.match(event.request)
                .then(response => response || fetch(event.request))
        );
    }
});
```

## 🔄 Deployment

### Production Checklist

```bash
# Environment setup
cp .env.example .env.production
php artisan key:generate

# Dependencies
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# Database
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder

# Optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart

# Storage
php artisan storage:link
```

### Queue Configuration

```php
// config/queue.php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],
```

## 🐛 Common Pitfalls

### Livewire Gotchas

1. **Public Properties**: All public properties are sent to the frontend
   ```php
   // BAD - sensitive data exposed
   public $user; // Contains password hash
   
   // GOOD - only expose what's needed
   public int $userId;
   ```

2. **Method Security**: Public methods can be called from frontend
   ```php
   // BAD - no authorization
   public function deleteOrder($orderId) { ... }
   
   // GOOD - with authorization
   public function deleteOrder($orderId)
   {
       $order = Order::findOrFail($orderId);
       $this->authorize('delete', $order);
       // ...
   }
   ```

### Performance Issues

1. **N+1 Queries**: Always eager load relationships
2. **Large Collections**: Use pagination for large datasets
3. **Heavy Computations**: Move to queued jobs
4. **Real-time Updates**: Use broadcasting instead of polling

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [RestoPos API Documentation](./api-reference.md)
- [Database Schema](./database-schema.md)
- [Deployment Guide](./deployment.md)

---

*This guide follows our development philosophy of keeping things simple and leveraging Laravel/Livewire's built-in capabilities. For questions or contributions, see our [contributing guide](../contributing.md).*