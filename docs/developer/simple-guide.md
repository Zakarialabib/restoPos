# Simple Developer Guide - Pure Laravel/Livewire

This guide shows you how to build with **zero complexity** - just pure Laravel and Livewire doing what they do best.

## 🚀 Zero-Setup Development

### Prerequisites (Only what's needed)
- PHP 8.1+
- Composer
- MySQL/PostgreSQL
- Node.js (for Tailwind only)

### One-Command Setup
```bash
git clone https://github.com/restopos/restopos.git
cd restopos
./setup.sh  # This runs everything you need
```

## 🎯 Core Patterns (Copy-Paste Ready)

### 1. Livewire Component = Controller + View
Instead of creating separate controllers and views, just use Livewire:

```php
// app/Livewire/OrderManager.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderManager extends Component
{
    public $orders;
    public $search = '';
    
    public function mount()
    {
        $this->orders = Order::latest()->get();
    }
    
    public function updatedSearch()
    {
        $this->orders = Order::where('customer_name', 'like', "%{$this->search}%")
                           ->latest()
                           ->get();
    }
    
    public function markAsComplete($orderId)
    {
        Order::find($orderId)->update(['status' => 'completed']);
        $this->orders = Order::latest()->get();
    }
    
    public function render()
    {
        return view('livewire.order-manager');
    }
}
```

### 2. Actions = Simple Business Logic
Don't overthink it - just move complex logic out of components:

```php
// app/Actions/CreateOrderAction.php
namespace App\Actions;

use App\Models\Order;

class CreateOrderAction
{
    public function __invoke(array $data): Order
    {
        $order = Order::create($data);
        
        // Simple business logic
        if ($data['total'] > 100) {
            $order->addDiscount(10);
        }
        
        return $order;
    }
}
```

### 3. Routes = One Line Per Page
```php
// routes/web.php
use App\Livewire\OrderManager;

Route::get('/orders', OrderManager::class)->middleware('auth');
Route::get('/menu', App\Livewire\Customer\MenuDisplay::class);
Route::get('/admin', App\Livewire\Admin\Dashboard::class)->middleware('admin');
```

## 🏗️ Building Features (Step-by-Step)

### Create a Product Management System (5 minutes)

#### Step 1: Component
```bash
php artisan make:livewire Admin/ProductManager
```

#### Step 2: Logic
```php
// app/Livewire/Admin/ProductManager.php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class ProductManager extends Component
{
    use WithPagination;
    
    public $name = '';
    public $price = '';
    public $description = '';
    public $editingId = null;
    
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }
    
    public function save()
    {
        $this->validate();
        
        Product::updateOrCreate(
            ['id' => $this->editingId],
            $this->only(['name', 'price', 'description'])
        );
        
        $this->reset(['name', 'price', 'description', 'editingId']);
        session()->flash('message', 'Product saved!');
    }
    
    public function edit(Product $product)
    {
        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->description = $product->description;
    }
    
    public function delete(Product $product)
    {
        $product->delete();
        session()->flash('message', 'Product deleted!');
    }
    
    public function render()
    {
        return view('livewire.admin.product-manager', [
            'products' => Product::paginate(10)
        ]);
    }
}
```

#### Step 3: View (Copy-Paste)
```blade
<!-- resources/views/livewire/admin/product-manager.blade.php -->
<div class="p-6">
    @if (session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save" class="mb-6 bg-white p-4 rounded shadow">
        <div class="grid grid-cols-3 gap-4">
            <input wire:model="name" placeholder="Product name" class="border rounded px-3 py-2">
            <input wire:model="price" type="number" step="0.01" placeholder="Price" class="border rounded px-3 py-2">
            <input wire:model="description" placeholder="Description" class="border rounded px-3 py-2">
        </div>
        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
            {{ $editingId ? 'Update' : 'Add' }} Product
        </button>
    </form>

    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr class="border-b">
                <th class="text-left p-3">Name</th>
                <th class="text-left p-3">Price</th>
                <th class="text-left p-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="border-b">
                    <td class="p-3">{{ $product->name }}</td>
                    <td class="p-3">${{ number_format($product->price, 2) }}</td>
                    <td class="p-3">
                        <button wire:click="edit({{ $product->id }})" class="text-blue-500 mr-2">Edit</button>
                        <button wire:click="delete({{ $product->id }})" class="text-red-500" 
                                onclick="return confirm('Are you sure?')">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}
</div>
```

## 🎯 Real-World Examples

### Customer Ordering Flow
```php
// app/Livewire/Customer/OrderFlow.php
namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;

class OrderFlow extends Component
{
    public $category = 'all';
    public $cart = [];
    
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'product' => $product,
                'quantity' => 1
            ];
        }
    }
    
    public function getTotalProperty()
    {
        return collect($this->cart)->sum(function($item) {
            return $item['product']->price * $item['quantity'];
        });
    }
    
    public function placeOrder()
    {
        $order = Order::create([
            'customer_name' => 'Walk-in Customer',
            'total' => $this->total,
            'status' => 'pending'
        ]);
        
        foreach ($this->cart as $productId => $item) {
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['product']->price
            ]);
        }
        
        $this->cart = [];
        session()->flash('success', 'Order placed!');
    }
    
    public function render()
    {
        $products = $this->category === 'all' 
            ? Product::all() 
            : Product::where('category', $this->category)->get();
            
        return view('livewire.customer.order-flow', compact('products'));
    }
}
```

## 🔧 Testing (Keep It Simple)

```php
// tests/Feature/ProductManagementTest.php
namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Admin\ProductManager;

class ProductManagementTest extends TestCase
{
    public function test_can_create_product()
    {
        Livewire::test(ProductManager::class)
            ->set('name', 'Test Product')
            ->set('price', 9.99)
            ->call('save')
            ->assertSee('Product saved!');
    }
}
```

## 🚀 Deployment Checklist

1. **Environment Variables**
   ```bash
   cp .env.example .env.production
   # Edit only what you need
   ```

2. **Build Assets**
   ```bash
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Database**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

## 🎯 Common Patterns (Copy-Paste Library)

### Flash Messages
```php
// In component
session()->flash('message', 'Done!');

// In view
@if (session('message'))
    <div class="bg-green-100 p-3 rounded">{{ session('message') }}</div>
@endif
```

### Loading States
```blade
<button wire:loading.attr="disabled" class="bg-blue-500 text-white px-4 py-2">
    <span wire:loading>Processing...</span>
    <span wire:loading.remove>Save</span>
</button>
```

### Modal/Slide-over
```php
// In component
public $showModal = false;

public function openModal() { $this->showModal = true; }
public function closeModal() { $this->showModal = false; }
```

```blade
@if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg">
            <!-- Modal content -->
            <button wire:click="closeModal" class="text-gray-500">Close</button>
        </div>
    </div>
@endif
```

## 🚫 What NOT to Do

❌ **Don't create separate API controllers** - Livewire handles everything
❌ **Don't use Vue components** - Livewire is more than enough
❌ **Don't create complex service layers** - Actions are simpler
❌ **Don't over-engineer** - Keep it simple and working
❌ **Don't use repositories** - Eloquent is already perfect

## ✅ What TO Do

✅ **Use Livewire for everything UI**
✅ **Use Actions for business logic**
✅ **Use Eloquent directly**
✅ **Keep components small and focused**
✅ **Test with Livewire::test()**

## 🎉 Success Metrics

Your feature is ready when:
- [ ] It works without any external dependencies
- [ ] It has less than 100 lines of component code
- [ ] It passes basic Livewire tests
- [ ] It's mobile-responsive by default
- [ ] It follows the existing patterns

Remember: **If you're thinking about Vue, you're overthinking it.** Livewire + Laravel can do everything you need with less complexity and better developer experience.