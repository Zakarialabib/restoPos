# Developer Guide

Welcome to the RestoPos Developer Guide. This comprehensive guide covers everything you need to know to develop, customize, and extend the RestoPos restaurant management system.

## Table of Contents

- [Getting Started](#getting-started)
- [Architecture Overview](#architecture-overview)
- [Development Environment](#development-environment)
- [Core Concepts](#core-concepts)
- [Component Development](#component-development)
- [API Development](#api-development)
- [Testing](#testing)
- [Deployment](#deployment)
- [Best Practices](#best-practices)

## Getting Started

### Prerequisites

Before you begin development, ensure you have:

- **PHP 8.1+** with required extensions
- **Composer** for dependency management
- **Node.js 18+** and npm for frontend assets
- **MySQL 8.0+** or **PostgreSQL 13+**
- **Redis** for caching and sessions
- **Git** for version control

### Quick Setup

```bash
# Clone the repository
git clone https://github.com/your-org/restopos.git
cd restopos

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment configuration
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

### Environment Configuration

```env
# .env configuration
APP_NAME=RestoPos
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restopos
DB_USERNAME=root
DB_PASSWORD=

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Broadcasting (WebSockets)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-key
PUSHER_APP_SECRET=your-pusher-secret
PUSHER_APP_CLUSTER=mt1

# Queue Configuration
QUEUE_CONNECTION=redis

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@restopos.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Architecture Overview

### System Architecture

RestoPos follows a modular, domain-driven architecture:

```
RestoPos/
├── app/
│   ├── Actions/           # Business logic actions
│   ├── Http/
│   │   ├── Controllers/    # HTTP request handlers
│   │   ├── Livewire/      # Livewire components
│   │   └── Middleware/    # HTTP middleware
│   ├── Models/            # Eloquent models
│   ├── Policies/          # Authorization policies
│   ├── Jobs/              # Background jobs
│   ├── Events/            # Domain events
│   ├── Listeners/         # Event listeners
│   └── Services/          # Application services
├── database/
│   ├── migrations/        # Database migrations
│   ├── seeders/           # Database seeders
│   └── factories/         # Model factories
├── resources/
│   ├── views/             # Blade templates
│   ├── js/                # JavaScript assets
│   └── css/               # CSS assets
├── routes/                # Route definitions
├── tests/                 # Test suites
└── config/                # Configuration files
```

### Core Modules

#### 1. Customer Portal Module
- **Location**: `app/Http/Livewire/Customer/`
- **Purpose**: Handle customer-facing ordering interface
- **Key Components**:
  - `MenuDisplay` - Menu browsing and filtering
  - `ComposableBuilder` - Custom item creation
  - `CartManager` - Shopping cart functionality
  - `OrderCheckout` - Order placement and payment

#### 2. TV Display Module
- **Location**: `app/Http/Livewire/TvMenu/`
- **Purpose**: Manage TV display screens
- **Key Components**:
  - `TvMenuDisplay` - Main TV menu interface
  - `CategorySlider` - Category navigation
  - `ItemShowcase` - Featured item display
  - `PromotionBanner` - Promotional content

#### 3. Admin Management Module
- **Location**: `app/Http/Livewire/Admin/`
- **Purpose**: Administrative interface
- **Key Components**:
  - `ProductManager` - Menu item management
  - `OrderManager` - Order processing
  - `ComposableManager` - Composable product configuration
  - `ReportDashboard` - Analytics and reporting

#### 4. Kitchen Display Module
- **Location**: `app/Http/Livewire/Kitchen/`
- **Purpose**: Kitchen order management
- **Key Components**:
  - `OrderQueue` - Order processing queue
  - `OrderDetails` - Detailed order information
  - `StationManager` - Kitchen station management

### Technology Stack

#### Backend
- **Framework**: Laravel 10.x
- **Database**: MySQL 8.0+ / PostgreSQL 13+
- **Caching**: Redis
- **Queue System**: Redis-based queues
- **Real-time**: Laravel Echo + Pusher
- **Authentication**: Laravel Sanctum

#### Frontend
- **Framework**: Livewire 3.x
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js 3.x
- **Build Tool**: Vite
- **Icons**: Heroicons

#### Development Tools
- **Testing**: PHPUnit, Pest
- **Code Quality**: PHP CS Fixer, PHPStan
- **API Documentation**: Scribe
- **Debugging**: Laravel Telescope

## Development Environment

### Local Development Setup

#### Using Laravel Sail (Docker)

```bash
# Install Sail
composer require laravel/sail --dev

# Publish Sail configuration
php artisan sail:install

# Start the development environment
./vendor/bin/sail up -d

# Run migrations
./vendor/bin/sail artisan migrate

# Install frontend dependencies
./vendor/bin/sail npm install

# Build assets
./vendor/bin/sail npm run dev
```

#### Manual Setup

```bash
# Start MySQL/PostgreSQL
sudo systemctl start mysql

# Start Redis
sudo systemctl start redis

# Start the Laravel development server
php artisan serve

# Start the queue worker
php artisan queue:work

# Start the WebSocket server (if using Laravel WebSockets)
php artisan websockets:serve

# Watch for asset changes
npm run dev
```

### Development Tools

#### Laravel Telescope

```bash
# Install Telescope
composer require laravel/telescope --dev

# Publish Telescope assets
php artisan telescope:install

# Run migrations
php artisan migrate
```

Access Telescope at: `http://localhost:8000/telescope`

#### Laravel Debugbar

```bash
# Install Debugbar
composer require barryvdh/laravel-debugbar --dev

# Publish configuration
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

## Core Concepts

### Actions Pattern

RestoPos uses the Action pattern for business logic:

```php
<?php

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\Customer;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\DB;

class CreateOrderAction
{
    public function execute(array $orderData, Customer $customer): Order
    {
        return DB::transaction(function () use ($orderData, $customer) {
            // Create the order
            $order = Order::create([
                'customer_id' => $customer->id,
                'table_id' => $orderData['table_id'],
                'status' => 'pending',
                'total_amount' => $this->calculateTotal($orderData['items']),
                'notes' => $orderData['notes'] ?? null,
            ]);

            // Add order items
            foreach ($orderData['items'] as $itemData) {
                $order->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'customizations' => $itemData['customizations'] ?? [],
                    'special_instructions' => $itemData['instructions'] ?? null,
                ]);
            }

            // Dispatch order created event
            OrderCreated::dispatch($order);

            return $order;
        });
    }

    private function calculateTotal(array $items): float
    {
        return collect($items)->sum(function ($item) {
            return $item['unit_price'] * $item['quantity'];
        });
    }
}
```

### Livewire Components

Livewire components handle the interactive UI:

```php
<?php

namespace App\Http\Livewire\Customer;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class MenuDisplay extends Component
{
    use WithPagination;

    public $selectedCategory = null;
    public $searchTerm = '';
    public $sortBy = 'name';
    public $filters = [
        'dietary' => [],
        'allergens' => [],
        'price_range' => [0, 100]
    ];

    protected $queryString = [
        'selectedCategory' => ['except' => null],
        'searchTerm' => ['except' => ''],
        'sortBy' => ['except' => 'name']
    ];

    public function mount($categoryId = null)
    {
        $this->selectedCategory = $categoryId;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function addToCart($productId, $quantity = 1)
    {
        $this->dispatch('add-to-cart', [
            'product_id' => $productId,
            'quantity' => $quantity
        ]);

        $this->dispatch('show-notification', [
            'type' => 'success',
            'message' => 'Item added to cart!'
        ]);
    }

    public function render()
    {
        $categories = Category::with('products')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $products = Product::query()
            ->with(['category', 'media'])
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->when($this->searchTerm, function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
            })
            ->when($this->filters['dietary'], function ($query) {
                $query->whereJsonContains('dietary_tags', $this->filters['dietary']);
            })
            ->where('is_available', true)
            ->orderBy($this->sortBy)
            ->paginate(12);

        return view('livewire.customer.menu-display', [
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
```

### Model Relationships

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'is_available',
        'is_composable',
        'composable_config',
        'dietary_tags',
        'allergens',
        'preparation_time',
        'calories',
        'ingredients'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_composable' => 'boolean',
        'composable_config' => 'array',
        'dietary_tags' => 'array',
        'allergens' => 'array',
        'ingredients' => 'array',
        'price' => 'decimal:2'
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'product_media')
                    ->withPivot('type', 'sort_order')
                    ->orderBy('pivot_sort_order');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeComposable($query)
    {
        return $query->where('is_composable', true);
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Accessors & Mutators
    public function getPrimaryImageAttribute()
    {
        return $this->media()->where('pivot_type', 'primary')->first();
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    // Methods
    public function isComposable(): bool
    {
        return $this->is_composable && !empty($this->composable_config);
    }

    public function hasAllergen(string $allergen): bool
    {
        return in_array($allergen, $this->allergens ?? []);
    }

    public function hasDietaryTag(string $tag): bool
    {
        return in_array($tag, $this->dietary_tags ?? []);
    }
}
```

## Component Development

### Creating Livewire Components

```bash
# Create a new Livewire component
php artisan make:livewire Customer/ProductCard

# Create component with inline view
php artisan make:livewire Customer/ProductCard --inline

# Create component in subdirectory
php artisan make:livewire Admin/Products/ProductManager
```

### Component Structure

```php
<?php

namespace App\Http\Livewire\Customer;

use App\Models\Product;
use App\Actions\Cart\AddToCartAction;
use Livewire\Component;

class ProductCard extends Component
{
    public Product $product;
    public $quantity = 1;
    public $showDetails = false;

    protected $listeners = [
        'product-updated' => 'refreshProduct'
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function addToCart()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        app(AddToCartAction::class)->execute(
            $this->product,
            $this->quantity,
            auth()->user()
        );

        $this->dispatch('cart-updated');
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Added to cart!'
        ]);

        $this->quantity = 1;
    }

    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

    public function refreshProduct()
    {
        $this->product->refresh();
    }

    public function render()
    {
        return view('livewire.customer.product-card');
    }
}
```

### Component View

```blade
{{-- resources/views/livewire/customer/product-card.blade.php --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
    {{-- Product Image --}}
    <div class="relative h-48 bg-gray-200">
        @if($product->primaryImage)
            <img src="{{ $product->primaryImage->url }}" 
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover">
        @else
            <div class="flex items-center justify-center h-full text-gray-400">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif

        {{-- Availability Badge --}}
        @if(!$product->is_available)
            <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                Unavailable
            </div>
        @endif

        {{-- Dietary Tags --}}
        @if($product->dietary_tags)
            <div class="absolute bottom-2 left-2 flex space-x-1">
                @foreach($product->dietary_tags as $tag)
                    <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">
                        {{ ucfirst($tag) }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Product Info --}}
    <div class="p-4">
        <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
        
        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
            {{ $product->description }}
        </p>

        {{-- Price and Actions --}}
        <div class="flex items-center justify-between">
            <span class="text-xl font-bold text-green-600">
                {{ $product->formatted_price }}
            </span>

            @if($product->is_available)
                <div class="flex items-center space-x-2">
                    {{-- Quantity Selector --}}
                    <div class="flex items-center border rounded">
                        <button wire:click="$set('quantity', {{ max(1, $quantity - 1) }})"
                                class="px-2 py-1 hover:bg-gray-100">
                            -
                        </button>
                        <span class="px-3 py-1 border-x">{{ $quantity }}</span>
                        <button wire:click="$set('quantity', {{ min(10, $quantity + 1) }})"
                                class="px-2 py-1 hover:bg-gray-100">
                            +
                        </button>
                    </div>

                    {{-- Add to Cart Button --}}
                    <button wire:click="addToCart"
                            wire:loading.attr="disabled"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors disabled:opacity-50">
                        <span wire:loading.remove>Add to Cart</span>
                        <span wire:loading>Adding...</span>
                    </button>
                </div>
            @endif
        </div>

        {{-- Details Toggle --}}
        <button wire:click="toggleDetails" 
                class="mt-3 text-blue-500 hover:text-blue-600 text-sm">
            {{ $showDetails ? 'Hide Details' : 'Show Details' }}
        </button>

        {{-- Product Details --}}
        @if($showDetails)
            <div class="mt-3 pt-3 border-t">
                {{-- Preparation Time --}}
                @if($product->preparation_time)
                    <p class="text-sm text-gray-600 mb-2">
                        <strong>Prep Time:</strong> {{ $product->preparation_time }} minutes
                    </p>
                @endif

                {{-- Calories --}}
                @if($product->calories)
                    <p class="text-sm text-gray-600 mb-2">
                        <strong>Calories:</strong> {{ $product->calories }}
                    </p>
                @endif

                {{-- Allergens --}}
                @if($product->allergens)
                    <div class="mb-2">
                        <strong class="text-sm text-gray-600">Allergens:</strong>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($product->allergens as $allergen)
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">
                                    {{ ucfirst($allergen) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Ingredients --}}
                @if($product->ingredients)
                    <div>
                        <strong class="text-sm text-gray-600">Ingredients:</strong>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ implode(', ', $product->ingredients) }}
                        </p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
```

### Component Testing

```php
<?php

namespace Tests\Feature\Livewire\Customer;

use App\Http\Livewire\Customer\ProductCard;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ProductCardTest extends TestCase
{
    public function test_can_render_product_card()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 12.99,
            'is_available' => true
        ]);

        Livewire::test(ProductCard::class, ['product' => $product])
            ->assertSee('Test Product')
            ->assertSee('$12.99')
            ->assertSee('Add to Cart');
    }

    public function test_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['is_available' => true]);

        $this->actingAs($user);

        Livewire::test(ProductCard::class, ['product' => $product])
            ->set('quantity', 2)
            ->call('addToCart')
            ->assertDispatched('cart-updated')
            ->assertDispatched('show-toast');
    }

    public function test_cannot_add_unavailable_product_to_cart()
    {
        $product = Product::factory()->create(['is_available' => false]);

        Livewire::test(ProductCard::class, ['product' => $product])
            ->assertDontSee('Add to Cart');
    }

    public function test_can_toggle_product_details()
    {
        $product = Product::factory()->create([
            'allergens' => ['nuts', 'dairy'],
            'preparation_time' => 15
        ]);

        Livewire::test(ProductCard::class, ['product' => $product])
            ->assertSet('showDetails', false)
            ->call('toggleDetails')
            ->assertSet('showDetails', true)
            ->assertSee('Prep Time: 15 minutes')
            ->assertSee('nuts')
            ->assertSee('dairy');
    }
}
```

## API Development

### API Routes

```php
<?php

// routes/api.php
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\CategoryController;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        // Orders
        Route::apiResource('orders', OrderController::class);
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
        
        // Cart
        Route::get('/cart', [CartController::class, 'show']);
        Route::post('/cart/items', [CartController::class, 'addItem']);
        Route::put('/cart/items/{item}', [CartController::class, 'updateItem']);
        Route::delete('/cart/items/{item}', [CartController::class, 'removeItem']);
        Route::delete('/cart', [CartController::class, 'clear']);
    });

    // Admin routes
    Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
        Route::apiResource('products', ProductController::class)->except(['index', 'show']);
        Route::apiResource('categories', CategoryController::class)->except(['index']);
        Route::get('/analytics/sales', [AnalyticsController::class, 'sales']);
        Route::get('/analytics/popular-items', [AnalyticsController::class, 'popularItems']);
    });
});
```

### API Controllers

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\Api\ProductIndexRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): ProductCollection
    {
        $products = Product::query()
            ->with(['category', 'media'])
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->available_only, function ($query) {
                $query->where('is_available', true);
            })
            ->when($request->dietary_tags, function ($query, $tags) {
                foreach ($tags as $tag) {
                    $query->whereJsonContains('dietary_tags', $tag);
                }
            })
            ->orderBy($request->sort_by ?? 'name', $request->sort_direction ?? 'asc')
            ->paginate($request->per_page ?? 15);

        return new ProductCollection($products);
    }

    public function show(Product $product): ProductResource
    {
        $product->load(['category', 'media']);
        
        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());
        
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product->load(['category', 'media']))
        ], 201);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());
        
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product->load(['category', 'media']))
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    private function handleImageUploads(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');
            
            $product->media()->create([
                'filename' => $image->getClientOriginalName(),
                'path' => $path,
                'type' => $index === 0 ? 'primary' : 'gallery',
                'sort_order' => $index
            ]);
        }
    }
}
```

### API Resources

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'formatted_price' => $this->formatted_price,
            'is_available' => $this->is_available,
            'is_composable' => $this->is_composable,
            'preparation_time' => $this->preparation_time,
            'calories' => $this->calories,
            'dietary_tags' => $this->dietary_tags,
            'allergens' => $this->allergens,
            'ingredients' => $this->ingredients,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'images' => MediaResource::collection($this->whenLoaded('media')),
            'primary_image' => new MediaResource($this->whenLoaded('media', function () {
                return $this->media->where('pivot_type', 'primary')->first();
            })),
            'composable_config' => $this->when(
                $this->is_composable && $request->include_composable_config,
                $this->composable_config
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

### API Testing

```php
<?php

namespace Tests\Feature\Api\V1;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'description',
                             'price',
                             'is_available',
                             'category'
                         ]
                     ],
                     'links',
                     'meta'
                 ]);
    }

    public function test_can_filter_products_by_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        
        Product::factory()->count(2)->create(['category_id' => $category1->id]);
        Product::factory()->count(3)->create(['category_id' => $category2->id]);

        $response = $this->getJson("/api/v1/products?category_id={$category1->id}");

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_can_search_products()
    {
        Product::factory()->create(['name' => 'Chicken Burger']);
        Product::factory()->create(['name' => 'Beef Burger']);
        Product::factory()->create(['name' => 'Caesar Salad']);

        $response = $this->getJson('/api/v1/products?search=burger');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $productData = [
            'name' => 'New Product',
            'description' => 'A delicious new product',
            'price' => 15.99,
            'category_id' => $category->id,
            'is_available' => true,
            'dietary_tags' => ['vegetarian'],
            'allergens' => ['nuts']
        ];

        $response = $this->actingAs($admin, 'sanctum')
                         ->postJson('/api/v1/admin/products', $productData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'New Product',
                     'price' => 15.99
                 ]);

        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'price' => 15.99
        ]);
    }

    public function test_guest_cannot_create_product()
    {
        $category = Category::factory()->create();

        $productData = [
            'name' => 'New Product',
            'category_id' => $category->id
        ];

        $response = $this->postJson('/api/v1/admin/products', $productData);

        $response->assertStatus(401);
    }
}
```

## Testing

### Test Structure

```
tests/
├── Feature/           # Integration tests
│   ├── Api/           # API endpoint tests
│   ├── Livewire/      # Livewire component tests
│   └── Http/          # HTTP request tests
├── Unit/              # Unit tests
│   ├── Actions/       # Action class tests
│   ├── Models/        # Model tests
│   └── Services/      # Service class tests
└── Browser/           # Laravel Dusk tests
    ├── Customer/      # Customer flow tests
    ├── Admin/         # Admin interface tests
    └── TvDisplay/     # TV display tests
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run tests with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/Api/V1/ProductControllerTest.php

# Run tests in parallel
php artisan test --parallel

# Run browser tests
php artisan dusk
```

### Test Configuration

```php
<?php

// phpunit.xml
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="./vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">./app</directory>
        </include>
    </coverage>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

## Deployment

### Production Deployment

```bash
# Clone repository
git clone https://github.com/your-org/restopos.git
cd restopos

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci

# Set up environment
cp .env.example .env
# Edit .env with production values

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Build assets
npm run build

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Start queue workers
php artisan queue:restart
supervisorctl start restopos-worker:*
```

### Docker Deployment

```dockerfile
# Dockerfile
FROM php:8.1-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
```

```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build: .
    container_name: restopos-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - restopos

  webserver:
    image: nginx:alpine
    container_name: restopos-webserver
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d/:/etc/nginx/conf.d/
    networks:
      - restopos

  database:
    image: mysql:8.0
    container_name: restopos-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: restopos
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_PASSWORD: secret
      MYSQL_USER: restopos
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - restopos

  redis:
    image: redis:alpine
    container_name: restopos-redis
    restart: unless-stopped
    networks:
      - restopos

volumes:
  dbdata:
    driver: local

networks:
  restopos:
    driver: bridge
```

## Best Practices

### Code Organization

1. **Follow PSR Standards** - Use PSR-4 autoloading and PSR-12 coding style
2. **Single Responsibility** - Each class should have one reason to change
3. **Dependency Injection** - Use Laravel's service container
4. **Interface Segregation** - Create focused interfaces
5. **Repository Pattern** - For complex data access logic

### Performance Optimization

1. **Database Optimization**
   - Use proper indexing
   - Implement query optimization
   - Use eager loading to prevent N+1 queries
   - Implement database query caching

2. **Caching Strategy**
   - Cache frequently accessed data
   - Use Redis for session and cache storage
   - Implement HTTP caching headers
   - Cache compiled views and routes

3. **Asset Optimization**
   - Minify CSS and JavaScript
   - Use image optimization
   - Implement CDN for static assets
   - Enable gzip compression

### Security Best Practices

1. **Input Validation** - Always validate and sanitize user input
2. **SQL Injection Prevention** - Use Eloquent ORM and prepared statements
3. **XSS Protection** - Escape output and use CSP headers
4. **CSRF Protection** - Use Laravel's built-in CSRF protection
5. **Authentication** - Implement proper authentication and authorization
6. **HTTPS** - Always use HTTPS in production
7. **Rate Limiting** - Implement API rate limiting

### Monitoring and Logging

1. **Application Monitoring** - Use tools like New Relic or Datadog
2. **Error Tracking** - Implement Sentry or Bugsnag
3. **Performance Monitoring** - Monitor response times and database queries
4. **Log Management** - Use structured logging with proper log levels
5. **Health Checks** - Implement application health endpoints

---

**Next Steps:**
- [API Reference](../api/)
- [Testing Guide](./testing.md)
- [Deployment Guide](./deployment.md)
- [Contributing Guidelines](../resources/contributing.md)