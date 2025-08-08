# Step-by-Step Developer Guide

Welcome to the comprehensive step-by-step guide for RestoPos development. This guide will take you through the entire development process, from initial setup to advanced customization, in a structured, easy-to-follow manner.

## Table of Contents

1. [Getting Started](#1-getting-started)
2. [Understanding the Architecture](#2-understanding-the-architecture)
3. [Setting Up Your Development Environment](#3-setting-up-your-development-environment)
4. [Your First Component](#4-your-first-component)
5. [Working with the Database](#5-working-with-the-database)
6. [Building Customer Features](#6-building-customer-features)
7. [Creating TV Display Components](#7-creating-tv-display-components)
8. [Admin Panel Development](#8-admin-panel-development)
9. [API Development](#9-api-development)
10. [Testing Your Code](#10-testing-your-code)
11. [Deployment and Production](#11-deployment-and-production)

---

## 1. Getting Started

### Step 1.1: Prerequisites Check

Before diving into RestoPos development, ensure you have the following installed:

```bash
# Check PHP version (8.1 or higher required)
php -v

# Check Composer
composer --version

# Check Node.js (16.x or higher)
node --version
npm --version

# Check MySQL/PostgreSQL
mysql --version
# OR
psql --version

# Check Redis
redis-cli --version
```

**Required Extensions:**
```bash
# Verify PHP extensions
php -m | grep -E "(bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml|gd|curl)"
```

### Step 1.2: Clone and Initial Setup

```bash
# Clone the repository
git clone https://github.com/your-org/restopos.git
cd restopos

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 1.3: Environment Configuration

Edit your `.env` file with the following essential configurations:

```env
# Application
APP_NAME=RestoPos
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restopos_dev
DB_USERNAME=root
DB_PASSWORD=your_password

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Broadcasting (for real-time features)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-key
PUSHER_APP_SECRET=your-secret
PUSHER_APP_CLUSTER=mt1

# Queue
QUEUE_CONNECTION=redis
```

### Step 1.4: Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE restopos_dev;"

# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### Step 1.5: Build Assets and Start Development

```bash
# Build frontend assets
npm run dev

# Start the development server
php artisan serve

# In another terminal, start the queue worker
php artisan queue:work

# In another terminal, watch for asset changes
npm run dev
```

**Verification:**
- Visit `http://localhost:8000` - You should see the RestoPos landing page
- Visit `http://localhost:8000/admin` - Admin login page
- Visit `http://localhost:8000/customer/menu` - Customer menu interface

---

## 2. Understanding the Architecture

### Step 2.1: Project Structure Overview

Let's explore the key directories:

```
restopos/
├── app/
│   ├── Actions/              # Business logic (Action pattern)
│   ├── Http/
│   │   ├── Controllers/      # Traditional controllers
│   │   ├── Livewire/         # Livewire components (main UI logic)
│   │   └── Middleware/       # HTTP middleware
│   ├── Models/               # Eloquent models
│   ├── Policies/             # Authorization policies
│   └── Services/             # Application services
├── database/
│   ├── migrations/           # Database schema
│   └── seeders/              # Sample data
├── resources/
│   ├── views/                # Blade templates
│   ├── js/                   # JavaScript files
│   └── css/                  # Stylesheets
└── routes/                   # Route definitions
```

### Step 2.2: Core Concepts

**Actions Pattern:**
RestoPos uses Actions for business logic instead of fat controllers:

```php
// app/Actions/Orders/CreateOrderAction.php
class CreateOrderAction
{
    public function execute(array $orderData, Customer $customer): Order
    {
        // Business logic here
    }
}
```

**Livewire Components:**
Most UI interactions are handled by Livewire components:

```php
// app/Http/Livewire/Customer/MenuDisplay.php
class MenuDisplay extends Component
{
    public $selectedCategory = null;
    public $searchTerm = '';
    
    public function render()
    {
        return view('livewire.customer.menu-display');
    }
}
```

### Step 2.3: Module Structure

RestoPos is organized into four main modules:

1. **Customer Portal** (`app/Http/Livewire/Customer/`)
   - Menu browsing and ordering
   - Composable item builder
   - Cart management

2. **TV Display** (`app/Http/Livewire/TvMenu/`)
   - Digital menu displays
   - Promotional content
   - Real-time updates

3. **Admin Panel** (`app/Http/Livewire/Admin/`)
   - Product management
   - Order processing
   - Analytics and reporting

4. **Kitchen Display** (`app/Http/Livewire/Kitchen/`)
   - Order queue management
   - Preparation tracking
   - Station coordination

---

## 3. Setting Up Your Development Environment

### Step 3.1: IDE Configuration

**For VS Code:**

Install these extensions:
```json
{
    "recommendations": [
        "bmewburn.vscode-intelephense-client",
        "bradlc.vscode-tailwindcss",
        "onecentlin.laravel-blade",
        "ryannaddy.laravel-artisan",
        "codingyu.laravel-goto-view"
    ]
}
```

**For PhpStorm:**
- Enable Laravel plugin
- Configure PHP interpreter
- Set up database connection
- Configure Tailwind CSS support

### Step 3.2: Development Tools Setup

**Laravel Telescope (Debugging):**
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Access at: `http://localhost:8000/telescope`

**Laravel Debugbar:**
```bash
composer require barryvdh/laravel-debugbar --dev
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

**Browser Testing with Dusk:**
```bash
composer require laravel/dusk --dev
php artisan dusk:install
```

### Step 3.3: Git Workflow Setup

```bash
# Create development branch
git checkout -b feature/your-feature-name

# Set up pre-commit hooks (optional)
composer require --dev brianium/paratest
npm install --save-dev husky lint-staged
```

**Pre-commit configuration (.husky/pre-commit):**
```bash
#!/bin/sh
. "$(dirname "$0")/_/husky.sh"

# Run PHP CS Fixer
vendor/bin/php-cs-fixer fix --dry-run --diff

# Run tests
php artisan test --parallel

# Run ESLint
npm run lint
```

---

## 4. Your First Component

### Step 4.1: Understanding Livewire Components

Let's create a simple "Popular Items" component for the customer interface.

```bash
# Create the component
php artisan make:livewire Customer/PopularItems
```

This creates two files:
- `app/Http/Livewire/Customer/PopularItems.php` (Component class)
- `resources/views/livewire/customer/popular-items.blade.php` (View template)

### Step 4.2: Component Logic

Edit `app/Http/Livewire/Customer/PopularItems.php`:

```php
<?php

namespace App\Http\Livewire\Customer;

use App\Models\Product;
use Livewire\Component;

class PopularItems extends Component
{
    public $itemCount = 6;
    public $selectedCategory = null;
    
    protected $listeners = [
        'categoryChanged' => 'updateCategory'
    ];
    
    public function mount($category = null, $count = 6)
    {
        $this->selectedCategory = $category;
        $this->itemCount = $count;
    }
    
    public function updateCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }
    
    public function addToCart($productId)
    {
        // Dispatch event to cart component
        $this->dispatch('add-to-cart', [
            'product_id' => $productId,
            'quantity' => 1
        ]);
        
        // Show success message
        $this->dispatch('show-notification', [
            'type' => 'success',
            'message' => 'Item added to cart!'
        ]);
    }
    
    public function render()
    {
        $popularItems = Product::query()
            ->with(['category', 'media'])
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->where('is_available', true)
            ->orderByDesc('popularity_score')
            ->limit($this->itemCount)
            ->get();
            
        return view('livewire.customer.popular-items', [
            'popularItems' => $popularItems
        ]);
    }
}
```

### Step 4.3: Component View

Edit `resources/views/livewire/customer/popular-items.blade.php`:

```blade
<div class="popular-items-section">
    {{-- Section Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ $selectedCategory ? 'Popular in Category' : 'Popular Items' }}
        </h2>
        
        @if($popularItems->count() > 0)
            <span class="text-sm text-gray-500">
                Showing {{ $popularItems->count() }} items
            </span>
        @endif
    </div>
    
    {{-- Items Grid --}}
    @if($popularItems->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($popularItems as $item)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    {{-- Product Image --}}
                    <div class="relative h-48 bg-gray-200">
                        @if($item->primaryImage)
                            <img src="{{ $item->primaryImage->url }}" 
                                 alt="{{ $item->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                        
                        {{-- Popular Badge --}}
                        <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            Popular
                        </div>
                        
                        {{-- Dietary Tags --}}
                        @if($item->dietary_tags)
                            <div class="absolute bottom-2 left-2 flex space-x-1">
                                @foreach(array_slice($item->dietary_tags, 0, 2) as $tag)
                                    <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">
                                        {{ ucfirst($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    {{-- Product Info --}}
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 line-clamp-1">{{ $item->name }}</h3>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                            {{ $item->description }}
                        </p>
                        
                        {{-- Price and Action --}}
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-green-600">
                                ${{ number_format($item->price, 2) }}
                            </span>
                            
                            <button wire:click="addToCart({{ $item->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addToCart({{ $item->id }})"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="addToCart({{ $item->id }})">Add to Cart</span>
                                <span wire:loading wire:target="addToCart({{ $item->id }})">Adding...</span>
                            </button>
                        </div>
                        
                        {{-- Preparation Time --}}
                        @if($item->preparation_time)
                            <div class="mt-2 text-xs text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                {{ $item->preparation_time }} min
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No popular items found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your category selection.</p>
        </div>
    @endif
</div>
```

### Step 4.4: Using the Component

Now you can use this component in any Blade template:

```blade
{{-- In a customer page --}}
<div class="container mx-auto px-4 py-8">
    <livewire:customer.popular-items :count="8" />
</div>

{{-- With category filter --}}
<div class="container mx-auto px-4 py-8">
    <livewire:customer.popular-items :category="$categoryId" :count="6" />
</div>
```

### Step 4.5: Testing Your Component

Create a test file `tests/Feature/Livewire/Customer/PopularItemsTest.php`:

```php
<?php

namespace Tests\Feature\Livewire\Customer;

use App\Http\Livewire\Customer\PopularItems;
use App\Models\Product;
use App\Models\Category;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PopularItemsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_render_popular_items()
    {
        // Create test data
        $category = Category::factory()->create();
        $products = Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'is_available' => true,
            'popularity_score' => 100
        ]);
        
        // Test component rendering
        Livewire::test(PopularItems::class)
            ->assertSee('Popular Items')
            ->assertSee($products->first()->name)
            ->assertSee($products->first()->formatted_price);
    }
    
    public function test_can_add_item_to_cart()
    {
        $product = Product::factory()->create(['is_available' => true]);
        
        Livewire::test(PopularItems::class)
            ->call('addToCart', $product->id)
            ->assertDispatched('add-to-cart')
            ->assertDispatched('show-notification');
    }
    
    public function test_can_filter_by_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        
        $product1 = Product::factory()->create(['category_id' => $category1->id]);
        $product2 = Product::factory()->create(['category_id' => $category2->id]);
        
        Livewire::test(PopularItems::class, ['category' => $category1->id])
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);
    }
}
```

Run the test:
```bash
php artisan test tests/Feature/Livewire/Customer/PopularItemsTest.php
```

---

## 5. Working with the Database

### Step 5.1: Understanding the Database Schema

Let's examine the key tables in RestoPos:

```bash
# View existing migrations
ls database/migrations/

# Check current database structure
php artisan db:show
php artisan db:table products
```

**Core Tables:**
- `products` - Menu items and composable products
- `categories` - Product categories
- `orders` - Customer orders
- `order_items` - Individual items in orders
- `composable_components` - Components for composable products
- `customers` - Customer information
- `tables` - Restaurant tables

### Step 5.2: Creating a New Migration

Let's add a "favorites" feature by creating a user favorites table:

```bash
# Create migration
php artisan make:migration create_customer_favorites_table
```

Edit the migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamp('favorited_at');
            $table->timestamps();
            
            // Ensure a customer can't favorite the same product twice
            $table->unique(['customer_id', 'product_id']);
            
            // Add indexes for performance
            $table->index(['customer_id', 'favorited_at']);
            $table->index(['product_id', 'favorited_at']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('customer_favorites');
    }
};
```

```bash
# Run the migration
php artisan migrate
```

### Step 5.3: Creating Models and Relationships

Create the Favorite model:

```bash
php artisan make:model CustomerFavorite
```

Edit `app/Models/CustomerFavorite.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerFavorite extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_id',
        'product_id',
        'favorited_at'
    ];
    
    protected $casts = [
        'favorited_at' => 'datetime'
    ];
    
    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    // Scopes
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('favorited_at', '>=', now()->subDays($days));
    }
}
```

Update the Customer model to include the relationship:

```php
// Add to app/Models/Customer.php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

public function favorites(): BelongsToMany
{
    return $this->belongsToMany(Product::class, 'customer_favorites')
                ->withPivot('favorited_at')
                ->withTimestamps()
                ->orderByPivot('favorited_at', 'desc');
}

public function favoriteProducts(): HasMany
{
    return $this->hasMany(CustomerFavorite::class);
}

public function hasFavorited(Product $product): bool
{
    return $this->favorites()->where('product_id', $product->id)->exists();
}
```

Update the Product model:

```php
// Add to app/Models/Product.php

public function favoritedBy(): BelongsToMany
{
    return $this->belongsToMany(Customer::class, 'customer_favorites')
                ->withPivot('favorited_at')
                ->withTimestamps();
}

public function favoritesCount(): int
{
    return $this->favoritedBy()->count();
}
```

### Step 5.4: Creating Seeders

Create a seeder for sample favorites:

```bash
php artisan make:seeder CustomerFavoriteSeeder
```

Edit `database/seeders/CustomerFavoriteSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerFavorite;
use Illuminate\Database\Seeder;

class CustomerFavoriteSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();
        $products = Product::where('is_available', true)->get();
        
        foreach ($customers as $customer) {
            // Each customer favorites 3-8 random products
            $favoriteCount = rand(3, 8);
            $favoriteProducts = $products->random($favoriteCount);
            
            foreach ($favoriteProducts as $product) {
                CustomerFavorite::create([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'favorited_at' => now()->subDays(rand(1, 90))
                ]);
            }
        }
    }
}
```

Add to `database/seeders/DatabaseSeeder.php`:

```php
public function run()
{
    // ... existing seeders
    $this->call(CustomerFavoriteSeeder::class);
}
```

```bash
# Run the seeder
php artisan db:seed --class=CustomerFavoriteSeeder
```

### Step 5.5: Creating Factory

Create a factory for testing:

```bash
php artisan make:factory CustomerFavoriteFactory
```

Edit `database/factories/CustomerFavoriteFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFavoriteFactory extends Factory
{
    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'product_id' => Product::factory(),
            'favorited_at' => $this->faker->dateTimeBetween('-3 months', 'now')
        ];
    }
}
```

---

## 6. Building Customer Features

### Step 6.1: Customer Favorites Component

Now let's create a component to manage customer favorites:

```bash
php artisan make:livewire Customer/FavoriteButton
```

Edit `app/Http/Livewire/Customer/FavoriteButton.php`:

```php
<?php

namespace App\Http\Livewire\Customer;

use App\Models\Product;
use App\Models\CustomerFavorite;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FavoriteButton extends Component
{
    public Product $product;
    public bool $isFavorited = false;
    public int $favoritesCount = 0;
    
    protected $listeners = [
        'favorites-updated' => 'refreshFavoriteStatus'
    ];
    
    public function mount(Product $product)
    {
        $this->product = $product;
        $this->refreshFavoriteStatus();
    }
    
    public function toggleFavorite()
    {
        if (!Auth::guard('customer')->check()) {
            $this->dispatch('show-login-modal');
            return;
        }
        
        $customer = Auth::guard('customer')->user();
        
        if ($this->isFavorited) {
            // Remove from favorites
            CustomerFavorite::where('customer_id', $customer->id)
                           ->where('product_id', $this->product->id)
                           ->delete();
                           
            $this->dispatch('show-notification', [
                'type' => 'info',
                'message' => 'Removed from favorites'
            ]);
        } else {
            // Add to favorites
            CustomerFavorite::create([
                'customer_id' => $customer->id,
                'product_id' => $this->product->id,
                'favorited_at' => now()
            ]);
            
            $this->dispatch('show-notification', [
                'type' => 'success',
                'message' => 'Added to favorites!'
            ]);
        }
        
        $this->refreshFavoriteStatus();
        $this->dispatch('favorites-updated');
    }
    
    public function refreshFavoriteStatus()
    {
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            $this->isFavorited = $customer->hasFavorited($this->product);
        } else {
            $this->isFavorited = false;
        }
        
        $this->favoritesCount = $this->product->favoritesCount();
    }
    
    public function render()
    {
        return view('livewire.customer.favorite-button');
    }
}
```

Edit `resources/views/livewire/customer/favorite-button.blade.php`:

```blade
<div class="favorite-button-container">
    <button wire:click="toggleFavorite"
            wire:loading.attr="disabled"
            class="flex items-center space-x-1 px-3 py-2 rounded-lg transition-all duration-200 {{ $isFavorited ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        
        {{-- Heart Icon --}}
        <svg wire:loading.remove 
             class="w-5 h-5 transition-transform duration-200 {{ $isFavorited ? 'scale-110' : '' }}" 
             fill="{{ $isFavorited ? 'currentColor' : 'none' }}" 
             stroke="currentColor" 
             viewBox="0 0 24 24">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        
        {{-- Loading Spinner --}}
        <svg wire:loading 
             class="w-5 h-5 animate-spin" 
             fill="none" 
             viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        
        {{-- Favorites Count --}}
        @if($favoritesCount > 0)
            <span class="text-sm font-medium">{{ $favoritesCount }}</span>
        @endif
        
        {{-- Text Label --}}
        <span class="text-sm font-medium hidden sm:inline">
            {{ $isFavorited ? 'Favorited' : 'Favorite' }}
        </span>
    </button>
</div>
```

### Step 6.2: Customer Favorites Page

Create a full favorites page:

```bash
php artisan make:livewire Customer/FavoritesPage
```

Edit `app/Http/Livewire/Customer/FavoritesPage.php`:

```php
<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class FavoritesPage extends Component
{
    use WithPagination;
    
    public $sortBy = 'favorited_at';
    public $sortDirection = 'desc';
    public $filterCategory = null;
    
    protected $queryString = [
        'sortBy' => ['except' => 'favorited_at'],
        'sortDirection' => ['except' => 'desc'],
        'filterCategory' => ['except' => null]
    ];
    
    protected $listeners = [
        'favorites-updated' => '$refresh'
    ];
    
    public function mount()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }
    }
    
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }
    
    public function filterByCategory($categoryId)
    {
        $this->filterCategory = $categoryId;
        $this->resetPage();
    }
    
    public function clearFilters()
    {
        $this->filterCategory = null;
        $this->resetPage();
    }
    
    public function render()
    {
        $customer = Auth::guard('customer')->user();
        
        $favorites = $customer->favorites()
            ->with(['category', 'media'])
            ->when($this->filterCategory, function ($query) {
                $query->where('category_id', $this->filterCategory);
            })
            ->when($this->sortBy === 'favorited_at', function ($query) {
                $query->orderByPivot('favorited_at', $this->sortDirection);
            })
            ->when($this->sortBy === 'name', function ($query) {
                $query->orderBy('name', $this->sortDirection);
            })
            ->when($this->sortBy === 'price', function ($query) {
                $query->orderBy('price', $this->sortDirection);
            })
            ->paginate(12);
            
        $categories = $customer->favorites()
            ->with('category')
            ->get()
            ->pluck('category')
            ->unique('id')
            ->sortBy('name');
            
        return view('livewire.customer.favorites-page', [
            'favorites' => $favorites,
            'categories' => $categories
        ]);
    }
}
```

Edit `resources/views/livewire/customer/favorites-page.blade.php`:

```blade
<div class="favorites-page">
    {{-- Page Header --}}
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Favorites</h1>
                    <p class="text-gray-600 mt-1">{{ $favorites->total() }} favorite items</p>
                </div>
                
                {{-- Sort Options --}}
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <select wire:model="sortBy" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="favorited_at">Recently Added</option>
                            <option value="name">Name</option>
                            <option value="price">Price</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    
                    <button wire:click="sortBy('{{ $sortBy }}')"
                            class="p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5 {{ $sortDirection === 'desc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mx-auto px-4 py-8">
        {{-- Category Filters --}}
        @if($categories->count() > 0)
            <div class="mb-8">
                <div class="flex flex-wrap gap-2">
                    <button wire:click="clearFilters"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filterCategory === null ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        All Categories
                    </button>
                    
                    @foreach($categories as $category)
                        <button wire:click="filterByCategory({{ $category->id }})"
                                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $filterCategory == $category->id ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
        
        {{-- Favorites Grid --}}
        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($favorites as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
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
                            
                            {{-- Favorite Button --}}
                            <div class="absolute top-2 right-2">
                                <livewire:customer.favorite-button :product="$product" :key="'fav-'.$product->id" />
                            </div>
                            
                            {{-- Favorited Date --}}
                            <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                {{ $product->pivot->favorited_at->diffForHumans() }}
                            </div>
                        </div>
                        
                        {{-- Product Info --}}
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2 line-clamp-1">{{ $product->name }}</h3>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ $product->description }}
                            </p>
                            
                            {{-- Price and Actions --}}
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-green-600">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                                
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No favorites yet</h3>
                <p class="mt-2 text-gray-500">Start browsing our menu and add items to your favorites!</p>
                <div class="mt-6">
                    <a href="{{ route('customer.menu') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors">
                        Browse Menu
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
```

### Step 6.3: Adding Routes

Add routes for the favorites functionality in `routes/web.php`:

```php
// Customer routes
Route::prefix('customer')->name('customer.')->group(function () {
    // ... existing routes
    
    Route::middleware('auth:customer')->group(function () {
        Route::get('/favorites', FavoritesPage::class)->name('favorites');
    });
});
```

### Step 6.4: Integration with Existing Components

Update your existing product cards to include the favorite button. In any product display component, add:

```blade
{{-- Add this to your product card template --}}
<div class="absolute top-2 right-2">
    <livewire:customer.favorite-button :product="$product" :key="'fav-btn-'.$product->id" />
</div>
```

This completes the customer favorites feature. You now have:
- A reusable favorite button component
- A complete favorites page with filtering and sorting
- Database relationships and migrations
- Proper testing structure

Next, we'll move on to creating TV display components in Step 7.

---

## 7. Creating TV Display Components

### Step 7.1: Understanding TV Display Requirements

TV displays in RestoPos are designed for:
- **Non-interactive displays** - No touch input, just visual content
- **Auto-refresh** - Content updates automatically
- **Full-screen mode** - Optimized for large screens
- **Real-time updates** - Menu changes reflect immediately
- **Multiple display modes** - Menu, promotions, queue status

### Step 7.2: Creating a TV Menu Display Component

```bash
php artisan make:livewire TvMenu/MenuDisplay
```

Edit `app/Http/Livewire/TvMenu/MenuDisplay.php`:

```php
<?php

namespace App\Http\Livewire\TvMenu;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Collection;

class MenuDisplay extends Component
{
    public $displayMode = 'categories'; // categories, featured, promotions
    public $currentCategoryIndex = 0;
    public $itemsPerPage = 8;
    public $autoRotateInterval = 10; // seconds
    public $showPrices = true;
    public $theme = 'default';
    
    protected $queryString = [
        'displayMode' => ['except' => 'categories'],
        'theme' => ['except' => 'default'],
        'showPrices' => ['except' => true]
    ];
    
    public function mount()
    {
        // Auto-rotate categories every X seconds
        $this->dispatch('start-auto-rotate', [
            'interval' => $this->autoRotateInterval * 1000
        ]);
    }
    
    public function nextCategory()
    {
        $totalCategories = Category::where('is_active', true)->count();
        $this->currentCategoryIndex = ($this->currentCategoryIndex + 1) % $totalCategories;
    }
    
    public function setDisplayMode($mode)
    {
        $this->displayMode = $mode;
        $this->currentCategoryIndex = 0;
    }
    
    public function getCurrentCategory()
    {
        return Category::where('is_active', true)
                      ->orderBy('sort_order')
                      ->skip($this->currentCategoryIndex)
                      ->first();
    }
    
    public function getFeaturedProducts(): Collection
    {
        return Product::where('is_featured', true)
                     ->where('is_available', true)
                     ->with(['category', 'media'])
                     ->orderBy('featured_order')
                     ->limit($this->itemsPerPage)
                     ->get();
    }
    
    public function getCategoryProducts(): Collection
    {
        $category = $this->getCurrentCategory();
        
        if (!$category) {
            return collect();
        }
        
        return $category->products()
                       ->where('is_available', true)
                       ->with(['media'])
                       ->orderBy('sort_order')
                       ->limit($this->itemsPerPage)
                       ->get();
    }
    
    public function render()
    {
        $data = [];
        
        switch ($this->displayMode) {
            case 'featured':
                $data['products'] = $this->getFeaturedProducts();
                $data['title'] = 'Featured Items';
                break;
                
            case 'categories':
                $data['products'] = $this->getCategoryProducts();
                $data['category'] = $this->getCurrentCategory();
                $data['title'] = $data['category']->name ?? 'Menu';
                break;
                
            case 'promotions':
                $data['products'] = Product::where('is_on_promotion', true)
                                          ->where('is_available', true)
                                          ->with(['category', 'media'])
                                          ->limit($this->itemsPerPage)
                                          ->get();
                $data['title'] = 'Special Offers';
                break;
        }
        
        $data['categories'] = Category::where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->get();
        
        return view('livewire.tv-menu.menu-display', $data);
    }
}
```

Edit `resources/views/livewire/tv-menu/menu-display.blade.php`:

```blade
<div class="tv-menu-display h-screen bg-gradient-to-br from-blue-900 to-purple-900 text-white overflow-hidden"
     x-data="{ autoRotate: true }"
     x-init="
         setInterval(() => {
             if (autoRotate) {
                 $wire.nextCategory();
             }
         }, {{ $autoRotateInterval * 1000 }});
     ">
    
    {{-- Header Section --}}
    <div class="bg-black bg-opacity-30 p-6">
        <div class="flex items-center justify-between">
            {{-- Restaurant Logo/Name --}}
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-blue-900">RP</span>
                </div>
                <div>
                    <h1 class="text-4xl font-bold">{{ config('app.restaurant_name', 'RestoPos') }}</h1>
                    <p class="text-xl opacity-80">{{ $title }}</p>
                </div>
            </div>
            
            {{-- Current Time --}}
            <div class="text-right">
                <div class="text-3xl font-bold" x-data x-text="new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"
                     x-init="setInterval(() => { $el.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }, 1000)"></div>
                <div class="text-lg opacity-80" x-data x-text="new Date().toLocaleDateString('en-US', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})"></div>
            </div>
        </div>
        
        {{-- Category Navigation (for categories mode) --}}
        @if($displayMode === 'categories' && isset($categories))
            <div class="mt-4 flex justify-center">
                <div class="flex space-x-2">
                    @foreach($categories as $index => $cat)
                        <div class="w-3 h-3 rounded-full {{ $index === $currentCategoryIndex ? 'bg-white' : 'bg-white bg-opacity-30' }} transition-all duration-300"></div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    {{-- Main Content Area --}}
    <div class="flex-1 p-8">
        @if($products && $products->count() > 0)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 h-full">
                @foreach($products as $product)
                    <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl overflow-hidden transform hover:scale-105 transition-all duration-500 flex flex-col">
                        {{-- Product Image --}}
                        <div class="relative h-48 bg-gray-800 bg-opacity-50">
                            @if($product->primaryImage)
                                <img src="{{ $product->primaryImage->url }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-white text-opacity-50">
                                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                            
                            {{-- Badges --}}
                            <div class="absolute top-2 left-2 flex flex-col space-y-1">
                                @if($product->is_featured)
                                    <span class="bg-yellow-500 text-black px-2 py-1 rounded-full text-xs font-bold">
                                        Featured
                                    </span>
                                @endif
                                
                                @if($product->is_on_promotion)
                                    <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                        Special
                                    </span>
                                @endif
                                
                                @if($product->dietary_tags)
                                    @foreach(array_slice($product->dietary_tags, 0, 2) as $tag)
                                        <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                            {{ strtoupper($tag) }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        {{-- Product Info --}}
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-2 line-clamp-2">{{ $product->name }}</h3>
                                
                                @if($product->description)
                                    <p class="text-white text-opacity-80 text-sm line-clamp-3 mb-3">
                                        {{ $product->description }}
                                    </p>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between">
                                @if($showPrices)
                                    <div class="text-right">
                                        @if($product->is_on_promotion && $product->promotion_price)
                                            <div class="text-lg line-through text-white text-opacity-60">
                                                ${{ number_format($product->price, 2) }}
                                            </div>
                                            <div class="text-2xl font-bold text-yellow-400">
                                                ${{ number_format($product->promotion_price, 2) }}
                                            </div>
                                        @else
                                            <div class="text-2xl font-bold text-green-400">
                                                ${{ number_format($product->price, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                {{-- Preparation Time --}}
                                @if($product->preparation_time)
                                    <div class="flex items-center text-white text-opacity-80 text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $product->preparation_time }}min
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <svg class="mx-auto h-24 w-24 text-white text-opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-4 text-2xl font-medium text-white text-opacity-80">No items to display</h3>
                    <p class="mt-2 text-white text-opacity-60">Please check back later for updates.</p>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Footer --}}
    <div class="bg-black bg-opacity-30 p-4">
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center space-x-6">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Scan QR code to order
                </span>
                
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                    </svg>
                    Call for assistance
                </span>
            </div>
            
            <div class="text-white text-opacity-60">
                Auto-updating every {{ $autoRotateInterval }} seconds
            </div>
        </div>
    </div>
</div>

{{-- Auto-rotation control script --}}
<script>
    document.addEventListener('livewire:load', function () {
        // Listen for display mode changes
        Livewire.on('display-mode-changed', function (mode) {
            console.log('Display mode changed to:', mode);
        });
        
        // Handle keyboard controls for manual navigation
        document.addEventListener('keydown', function (e) {
            switch(e.key) {
                case 'ArrowRight':
                case 'Space':
                    @this.nextCategory();
                    break;
                case 'f':
                case 'F':
                    @this.setDisplayMode('featured');
                    break;
                case 'c':
                case 'C':
                    @this.setDisplayMode('categories');
                    break;
                case 'p':
                case 'P':
                    @this.setDisplayMode('promotions');
                    break;
            }
        });
    });
</script>

### Step 7.3: Creating TV Display Routes and Controller

Add routes for TV displays in `routes/web.php`:

```php
// TV Display routes
Route::prefix('tv')->name('tv.')->group(function () {
    Route::get('/menu', TvMenu\MenuDisplay::class)->name('menu');
    Route::get('/promotions', TvMenu\MenuDisplay::class)->defaults('displayMode', 'promotions')->name('promotions');
    Route::get('/featured', TvMenu\MenuDisplay::class)->defaults('displayMode', 'featured')->name('featured');
});
```

### Step 7.4: TV Display Configuration

Create a configuration file for TV displays:

```bash
php artisan make:command CreateTvDisplayConfig
```

Edit `app/Console/Commands/CreateTvDisplayConfig.php`:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateTvDisplayConfig extends Command
{
    protected $signature = 'tv:config {--force : Overwrite existing config}';
    protected $description = 'Create TV display configuration file';
    
    public function handle()
    {
        $configPath = config_path('tv-display.php');
        
        if (File::exists($configPath) && !$this->option('force')) {
            $this->error('Configuration file already exists. Use --force to overwrite.');
            return 1;
        }
        
        $config = <<<'PHP'
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TV Display Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for TV menu displays throughout the restaurant
    |
    */
    
    'default_mode' => env('TV_DEFAULT_MODE', 'categories'),
    
    'auto_rotate_interval' => env('TV_AUTO_ROTATE_INTERVAL', 10), // seconds
    
    'items_per_page' => env('TV_ITEMS_PER_PAGE', 8),
    
    'show_prices' => env('TV_SHOW_PRICES', true),
    
    'themes' => [
        'default' => [
            'name' => 'Default Blue',
            'primary_color' => '#1e40af',
            'secondary_color' => '#7c3aed',
            'background' => 'from-blue-900 to-purple-900'
        ],
        'green' => [
            'name' => 'Forest Green',
            'primary_color' => '#059669',
            'secondary_color' => '#0d9488',
            'background' => 'from-green-900 to-teal-900'
        ],
        'orange' => [
            'name' => 'Sunset Orange',
            'primary_color' => '#ea580c',
            'secondary_color' => '#dc2626',
            'background' => 'from-orange-900 to-red-900'
        ]
    ],
    
    'display_modes' => [
        'categories' => [
            'name' => 'Category Rotation',
            'description' => 'Automatically rotate through menu categories'
        ],
        'featured' => [
            'name' => 'Featured Items',
            'description' => 'Show only featured menu items'
        ],
        'promotions' => [
            'name' => 'Promotions',
            'description' => 'Show items currently on promotion'
        ]
    ],
    
    'refresh_interval' => env('TV_REFRESH_INTERVAL', 300), // seconds
    
    'enable_keyboard_controls' => env('TV_KEYBOARD_CONTROLS', true),
    
    'restaurant_info' => [
        'name' => env('RESTAURANT_NAME', 'RestoPos'),
        'logo_url' => env('RESTAURANT_LOGO_URL', null),
        'contact_info' => env('RESTAURANT_CONTACT', 'Call for assistance')
    ]
];
PHP;
        
        File::put($configPath, $config);
        
        $this->info('TV display configuration created successfully!');
        $this->line('Configuration file: ' . $configPath);
        
        return 0;
    }
}
```

Run the command:
```bash
php artisan tv:config
```

### Step 7.5: Testing TV Display Components

Create tests for the TV display:

```bash
php artisan make:test TvMenu/MenuDisplayTest
```

Edit `tests/Feature/TvMenu/MenuDisplayTest.php`:

```php
<?php

namespace Tests\Feature\TvMenu;

use App\Http\Livewire\TvMenu\MenuDisplay;
use App\Models\Category;
use App\Models\Product;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuDisplayTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_render_tv_menu_display()
    {
        $category = Category::factory()->create(['is_active' => true]);
        $products = Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'is_available' => true
        ]);
        
        Livewire::test(MenuDisplay::class)
            ->assertSee('RestoPos')
            ->assertSee($category->name)
            ->assertSee($products->first()->name);
    }
    
    public function test_can_switch_display_modes()
    {
        $featuredProduct = Product::factory()->create([
            'is_featured' => true,
            'is_available' => true
        ]);
        
        Livewire::test(MenuDisplay::class)
            ->call('setDisplayMode', 'featured')
            ->assertSet('displayMode', 'featured')
            ->assertSee('Featured Items')
            ->assertSee($featuredProduct->name);
    }
    
    public function test_can_rotate_categories()
    {
        $categories = Category::factory()->count(3)->create(['is_active' => true]);
        
        $component = Livewire::test(MenuDisplay::class)
            ->assertSet('currentCategoryIndex', 0);
            
        $component->call('nextCategory')
            ->assertSet('currentCategoryIndex', 1);
            
        $component->call('nextCategory')
            ->assertSet('currentCategoryIndex', 2);
            
        // Should wrap around to 0
        $component->call('nextCategory')
            ->assertSet('currentCategoryIndex', 0);
    }
    
    public function test_shows_promotional_items()
    {
        $promotionalProduct = Product::factory()->create([
            'is_on_promotion' => true,
            'is_available' => true,
            'promotion_price' => 9.99
        ]);
        
        Livewire::test(MenuDisplay::class)
            ->call('setDisplayMode', 'promotions')
            ->assertSee('Special Offers')
            ->assertSee($promotionalProduct->name)
            ->assertSee('$9.99');
    }
}
```

---

## 8. Admin Panel Development

### Step 8.1: Understanding Admin Architecture

The RestoPos admin panel is built with:
- **Livewire components** for reactive interfaces
- **Role-based permissions** for different admin levels
- **Real-time updates** for order management
- **Analytics dashboards** for business insights

### Step 8.2: Creating an Admin Dashboard Component

```bash
php artisan make:livewire Admin/Dashboard
```

Edit `app/Http/Livewire/Admin/Dashboard.php`:

```php
<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $dateRange = '7'; // days
    public $refreshInterval = 30; // seconds
    
    protected $listeners = [
        'refresh-dashboard' => '$refresh',
        'date-range-changed' => 'updateDateRange'
    ];
    
    public function mount()
    {
        // Auto-refresh dashboard data
        $this->dispatch('start-auto-refresh', [
            'interval' => $this->refreshInterval * 1000
        ]);
    }
    
    public function updateDateRange($range)
    {
        $this->dateRange = $range;
    }
    
    public function getDateRangeStart()
    {
        return Carbon::now()->subDays($this->dateRange)->startOfDay();
    }
    
    public function getTodaysStats()
    {
        $today = Carbon::today();
        
        return [
            'orders' => Order::whereDate('created_at', $today)->count(),
            'revenue' => Order::whereDate('created_at', $today)
                            ->where('status', 'completed')
                            ->sum('total_amount'),
            'customers' => Customer::whereDate('created_at', $today)->count(),
            'avg_order_value' => Order::whereDate('created_at', $today)
                                    ->where('status', 'completed')
                                    ->avg('total_amount') ?? 0
        ];
    }
    
    public function getRecentOrders()
    {
        return Order::with(['customer', 'orderItems.product'])
                   ->latest()
                   ->limit(10)
                   ->get();
    }
    
    public function getTopProducts()
    {
        return DB::table('order_items')
                 ->join('products', 'order_items.product_id', '=', 'products.id')
                 ->join('orders', 'order_items.order_id', '=', 'orders.id')
                 ->where('orders.created_at', '>=', $this->getDateRangeStart())
                 ->where('orders.status', 'completed')
                 ->select(
                     'products.name',
                     'products.price',
                     DB::raw('SUM(order_items.quantity) as total_sold'),
                     DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
                 )
                 ->groupBy('products.id', 'products.name', 'products.price')
                 ->orderByDesc('total_sold')
                 ->limit(5)
                 ->get();
    }
    
    public function getRevenueChart()
    {
        $days = collect();
        $startDate = $this->getDateRangeStart();
        
        for ($i = 0; $i < $this->dateRange; $i++) {
            $date = $startDate->copy()->addDays($i);
            $revenue = Order::whereDate('created_at', $date)
                          ->where('status', 'completed')
                          ->sum('total_amount');
                          
            $days->push([
                'date' => $date->format('M j'),
                'revenue' => $revenue
            ]);
        }
        
        return $days;
    }
    
    public function getPendingOrdersCount()
    {
        return Order::whereIn('status', ['pending', 'confirmed', 'preparing'])->count();
    }
    
    public function getLowStockProducts()
    {
        return Product::where('stock_quantity', '<=', DB::raw('low_stock_threshold'))
                     ->where('track_stock', true)
                     ->orderBy('stock_quantity')
                     ->limit(5)
                     ->get();
    }
    
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'todaysStats' => $this->getTodaysStats(),
            'recentOrders' => $this->getRecentOrders(),
            'topProducts' => $this->getTopProducts(),
            'revenueChart' => $this->getRevenueChart(),
            'pendingOrders' => $this->getPendingOrdersCount(),
            'lowStockProducts' => $this->getLowStockProducts()
        ]);
    }
}
```

Edit `resources/views/livewire/admin/dashboard.blade.php`:

```blade
<div class="admin-dashboard" wire:poll.30s>
    {{-- Dashboard Header --}}
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-600">Welcome back! Here's what's happening today.</p>
                </div>
                
                {{-- Date Range Selector --}}
                <div class="flex items-center space-x-4">
                    <select wire:model="dateRange" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Last 24 hours</option>
                        <option value="7">Last 7 days</option>
                        <option value="30">Last 30 days</option>
                        <option value="90">Last 90 days</option>
                    </select>
                    
                    <button wire:click="$refresh" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Today's Orders --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Today's Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todaysStats['orders'] }}</p>
                </div>
            </div>
        </div>
        
        {{-- Today's Revenue --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Today's Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($todaysStats['revenue'], 2) }}</p>
                </div>
            </div>
        </div>
        
        {{-- New Customers --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">New Customers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todaysStats['customers'] }}</p>
                </div>
            </div>
        </div>
        
        {{-- Average Order Value --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Order Value</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($todaysStats['avg_order_value'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend</h3>
            <div class="h-64">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        {{-- Pending Orders Alert --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pending Orders</h3>
                @if($pendingOrders > 0)
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $pendingOrders }} pending
                    </span>
                @endif
            </div>
            
            @if($pendingOrders > 0)
                <div class="text-center py-4">
                    <svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">{{ $pendingOrders }} orders need attention</p>
                    <a href="{{ route('admin.orders') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        View Orders
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">All orders are up to date!</p>
                </div>
            @endif
        </div>
    </div>
    
    {{-- Bottom Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        {{-- Recent Orders --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentOrders as $order)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    Order #{{ $order->id }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $order->customer->name ?? 'Guest' }} • {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    ${{ number_format($order->total_amount, 2) }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-gray-500">No recent orders</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        {{-- Top Products --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Products ({{ $dateRange }} days)</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($topProducts as $product)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->total_sold }} sold</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    ${{ number_format($product->total_revenue, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-gray-500">No sales data available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Chart.js Integration --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = @json($revenueChart);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.date),
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.map(item => item.revenue),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush