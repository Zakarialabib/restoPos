# RestoPos Simplified Documentation

This documentation follows the principle: **"less with more"** - using only native Laravel/Livewire without any external frameworks or unnecessary complexity.

## 🎯 Core Philosophy

- **100% Native Laravel/Livewire**
- **No Vue components**
- **No extra layers**
- **Keep it simple, keep it working**

## 📋 Quick Start (5 minutes)

### 1. Installation
```bash
git clone https://github.com/restopos/restopos.git
cd restopos
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

### 2. Access Points
- **Admin Panel**: http://localhost:8000/admin
- **Customer Portal**: http://localhost:8000
- **TV Display**: http://localhost:8000/tv
- **Kitchen Display**: http://localhost:8000/kitchen

## 🏗️ Architecture Overview

### Directory Structure (Simplified)
```
app/
├── Models/          # Eloquent models only
├── Livewire/        # All UI components
├── Actions/         # Business logic helpers
├── Policies/        # Authorization rules
database/
├── migrations/      # Database structure
└── seeders/         # Sample data
resources/
├── views/           # Blade templates
└── css/             # Tailwind CSS
```

### Key Components

#### 1. Customer Portal (`app/Livewire/Customer/`)
- **MenuDisplay** - Browse products
- **OrderBuilder** - Build orders
- **CartManager** - Manage cart
- **CheckoutForm** - Complete order

#### 2. Admin Panel (`app/Livewire/Admin/`)
- **Dashboard** - Overview stats
- **ProductManager** - Manage products
- **OrderManager** - Process orders
- **SettingsForm** - System settings

#### 3. TV Display (`app/Livewire/Tv/`)
- **MenuDisplay** - Show menu items
- **Promotions** - Display offers
- **RealTimeUpdates** - Live content

#### 4. Kitchen Display (`app/Livewire/Kitchen/`)
- **OrderQueue** - Pending orders
- **OrderTracker** - Track progress
- **StationManager** - Kitchen stations

## 🚀 Building New Features

### Step 1: Create Livewire Component
```bash
php artisan make:livewire Customer/ProductCard
```

### Step 2: Add Business Logic
```php
// app/Livewire/Customer/ProductCard.php
namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Product;

class ProductCard extends Component
{
    public Product $product;
    
    public function addToCart()
    {
        $this->dispatch('product-added', productId: $this->product->id);
    }
    
    public function render()
    {
        return view('livewire.customer.product-card');
    }
}
```

### Step 3: Create Blade View
```blade
<!-- resources/views/livewire/customer/product-card.blade.php -->
<div class="bg-white rounded-lg shadow p-4">
    <img src="{{ $product->image_url }}" class="w-full h-48 object-cover rounded">
    <h3 class="text-lg font-semibold mt-2">{{ $product->name }}</h3>
    <p class="text-gray-600">{{ $product->description }}</p>
    <div class="flex justify-between items-center mt-4">
        <span class="text-xl font-bold">${{ $product->price }}</span>
        <button wire:click="addToCart" class="bg-blue-500 text-white px-4 py-2 rounded">
            Add to Cart
        </button>
    </div>
</div>
```

## 🎯 Essential Patterns

### 1. Actions Pattern (Simple)
```php
// app/Actions/CreateOrderAction.php
namespace App\Actions;

use App\Models\Order;

class CreateOrderAction
{
    public function __invoke(array $data): Order
    {
        return Order::create($data);
    }
}
```

### 2. Component Communication
```php
// Emitting events
$this->dispatch('order-updated', orderId: $order->id);

// Listening to events
protected $listeners = ['order-updated' => 'refreshOrders'];
```

### 3. Form Validation
```php
// In Livewire component
protected function rules()
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
    ];
}
```

## 📱 Mobile-First Design

All components are built with mobile-first approach:
- Responsive by default
- Touch-friendly buttons
- Optimized for QR code access
- No app download required

## 🔧 Common Tasks

### Add New Product
1. Go to Admin → Products
2. Click "Add Product"
3. Fill details and save
4. Product appears instantly on all displays

### Setup TV Display
1. Create TV display in Admin → Displays
2. Choose theme and products
3. Open TV URL on any screen
4. Content updates automatically

### Configure Composable Products
1. Create base product
2. Add ingredient groups
3. Set pricing rules
4. Test on customer portal

## 🚨 Troubleshooting

### Common Issues

**Livewire not updating?**
- Check browser console for errors
- Ensure Livewire scripts are loaded
- Verify component is properly mounted

**Styles not applying?**
- Run `npm run build`
- Clear browser cache
- Check Tailwind classes

**Database issues?**
- Run `php artisan migrate:fresh --seed`
- Check .env database settings
- Ensure MySQL is running

## 📚 Next Steps

1. **Explore existing components** in `app/Livewire/`
2. **Study the Actions** in `app/Actions/`
3. **Check the Models** in `app/Models/`
4. **Review the tests** in `tests/Feature/`

## 🤝 Contributing

1. Keep it simple - no unnecessary complexity
2. Use native Laravel features
3. Write tests for new features
4. Follow the existing patterns
5. Document your changes

## 📞 Support

- **Documentation**: This guide
- **Issues**: GitHub Issues
- **Community**: Join discussions
- **Email**: support@restopos.com

---

**Remember**: If you need Vue components, you're probably overthinking it. Livewire can do everything you need with less complexity and better performance.**