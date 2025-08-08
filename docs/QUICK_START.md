# RestoPos Quick Start - Zero Complexity

## 🎯 The Philosophy: Less is More

This is **RestoPos** - a complete restaurant management system built with **pure Laravel + Livewire**. No Vue, no complexity, just working code.

## 🚀 Get Running in 3 Commands

```bash
# 1. Clone and setup
git clone https://github.com/restopos/restopos.git && cd restopos

# 2. One-command setup (includes Docker)
./setup.sh

# 3. Access your system
open http://localhost:8000
```

That's it. You're running a complete restaurant system.

## 📱 What You Get Immediately

### Customer Portal (QR Code Ready)
- **URL**: `http://localhost:8000`
- **Use Case**: Customers scan QR code → order → pay
- **Features**: Mobile-first, no app download, real-time updates

### Admin Dashboard
- **URL**: `http://localhost:8000/admin`
- **Default**: admin@restopos.com / password
- **Features**: Full restaurant management

### TV Display
- **URL**: `http://localhost:8000/tv`
- **Use Case**: Digital menu boards, promotional displays
- **Setup**: Choose products → display URL on any screen

### Kitchen Display
- **URL**: `http://localhost:8000/kitchen`
- **Use Case**: Order queue for kitchen staff
- **Features**: Real-time order updates, preparation tracking

## 🏗️ Core Architecture (Simple)

```
app/
├── Models/          # Eloquent models (Product, Order, etc.)
├── Livewire/        # All UI components (replaces controllers + views)
├── Actions/         # Simple business logic helpers
database/
├── migrations/      # Database structure
└── seeders/         # Sample data
```

## 🎯 Building New Features (Copy-Paste Ready)

### 1. Create a Component (30 seconds)
```bash
php artisan make:livewire Customer/MenuDisplay
```

### 2. Add Logic (60 seconds)
```php
// app/Livewire/Customer/MenuDisplay.php
namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Product;

class MenuDisplay extends Component
{
    public $category = 'all';
    public $cart = [];
    
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        $this->cart[$productId] = ($this->cart[$productId] ?? 0) + 1;
        $this->dispatch('cart-updated');
    }
    
    public function render()
    {
        $products = $this->category === 'all' 
            ? Product::all() 
            : Product::where('category', $this->category)->get();
            
        return view('livewire.customer.menu-display', compact('products'));
    }
}
```

### 3. Add View (30 seconds)
```blade
<!-- resources/views/livewire/customer/menu-display.blade.php -->
<div class="p-4">
    <div class="grid grid-cols-2 gap-4">
        @foreach($products as $product)
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold">{{ $product->name }}</h3>
                <p class="text-gray-600">${{ $product->price }}</p>
                <button wire:click="addToCart({{ $product->id }})" 
                        class="mt-2 bg-blue-500 text-white px-3 py-1 rounded">
                    Add to Cart
                </button>
            </div>
        @endforeach
    </div>
</div>
```

### 4. Add Route (10 seconds)
```php
// routes/web.php
Route::get('/menu', \App\Livewire\Customer\MenuDisplay::class);
```

Done. Your feature is live.

## 🎯 Essential Patterns (Working Examples)

### Form Handling
```php
// In component
public $name = '';
public $email = '';

protected function rules()
{
    return [
        'name' => 'required|min:3',
        'email' => 'required|email',
    ];
}

public function save()
{
    $this->validate();
    User::create($this->all());
    session()->flash('message', 'Saved!');
}
```

### Real-time Updates
```php
// Emit events
$this->dispatch('order-updated');

// Listen in other components
protected $listeners = ['order-updated' => 'refreshData'];
```

### Database Queries
```php
// Simple and effective
Product::where('active', true)
       ->where('price', '>', 0)
       ->orderBy('name')
       ->get();
```

## 🎯 Common Tasks (Step-by-Step)

### Add New Product
1. Go to `http://localhost:8000/admin/products`
2. Click "Add Product"
3. Fill form → Save
4. Product appears instantly on all displays

### Setup TV Display
1. Create display: `http://localhost:8000/admin/displays`
2. Choose products and theme
3. Open `http://localhost:8000/tv` on any screen
4. Content updates automatically

### Configure Composable Products
1. Create base product (e.g., "Build Your Bowl")
2. Add ingredient groups (Protein, Vegetables, Sauce)
3. Set pricing rules
4. Test at `http://localhost:8000`

## 🚨 Troubleshooting (Simple Fixes)

### Livewire not updating?
```bash
# Clear cache
php artisan view:clear
php artisan cache:clear
npm run build
```

### Database issues?
```bash
# Reset everything
php artisan migrate:fresh --seed
```

### Styles broken?
```bash
npm run build
```

## 🎯 Testing (One Command)
```bash
# Test everything
./test.sh
```

## 📚 Next Steps

1. **Explore**: Check `app/Livewire/` for existing components
2. **Learn**: Read `app/Models/` to understand data structure
3. **Build**: Create your first component using patterns above
4. **Test**: Run `./test.sh` to verify everything works

## 🤝 Contributing Rules

1. **Keep it simple** - If you need Vue, you're overthinking
2. **Use native features** - Laravel already has everything
3. **Write tests** - One file, one test
4. **Document** - One README per component

## 📞 Support

- **Issues**: GitHub Issues
- **Community**: Discussions
- **Email**: support@restopos.com

---

## 🎯 Remember

**RestoPos works because it's simple.** 
- No build tools beyond npm run build
- No complex patterns beyond Livewire components
- No external dependencies beyond Laravel ecosystem
- Everything works out of the box

**If you're thinking about Vue components, stop. Livewire + Laravel can do everything you need with 10x less complexity.**

**Ready?** Run `./setup.sh` and start building.