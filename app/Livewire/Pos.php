<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class Pos extends Component
{
    // Product and category management
    public Collection $categories;
    public Collection $products;
    public  $activeCategory = '';
    public  $selectedCategory = 'all';
    public  $paymentMethod = 'cash';
    public  $discount = 0;
    public string $searchQuery = '';

    // Cart management
    public array $cart = [];
    public float $subtotal = 0;
    public float $taxRate = 0.08; // 8% tax rate
    public float $taxAmount = 0;
    public float $discountAmount = 0;
    public string $discountCode = ''; // For coupon input
    public float $total = 0;
    
    // Order management
    public ?Order $currentOrder = null;
    public string $customerName = '';
    public string $customerPhone = '';
    public string $customerEmail = '';
    public string $orderNotes = '';
    public string $orderType = 'dine-in'; // Default order type
    
    // UI state
    public bool $showReceiptModal = false;
    public array $receiptOrderData = []; // Ensure it's an array
    public bool $showPaymentModal = false;
    public ?string $paymentModalMethod = null; // 'cash', 'card', 'mobile'
    public float $amountTendered = 0;
    public float $changeDue = 0;

    public bool $showFilter = false;
    public bool $showPopular = false;

    protected $listeners = [
        'print-receipt' => 'printReceipt',
        'print-order' => 'printOrder'
    ];

    public function mount(): void
    {
        $this->loadCategories();
        $this->loadProducts(); // Load all products initially
    }

    private function loadCategories(): void
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function loadProducts(): void
    {
        $query = Product::query();

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
            });
        }

        if ($this->showPopular) {
            $query->where('is_featured', true);
        }

        $this->products = $query->orderBy('name')->get();
    }

    public function setActiveCategory($categoryId): void
    {
        $this->selectedCategory = $categoryId;
        $this->loadProducts();
    }

    public function updatedSearchQuery(): void
    {
        $this->activeCategory = ''; // Clear category filter when searching
        $this->loadProducts();
    }
    
    public function updatedOrderType(): void
    {
        // Potentially add logic here if order type affects anything immediately
        // e.g., applying different default service charges (not implemented here)
    }

    public function addToCart($productId, int $quantity = 1): void
    {
        $product = Product::find($productId);
        
        if (!$product) {
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] += $quantity;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'total' => $product->price * $quantity
            ];
        }

        $this->calculateTotals();
    }

    public function increaseQuantity($productId): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
            $this->cart[$productId]['total'] = $this->cart[$productId]['price'] * $this->cart[$productId]['quantity'];
            $this->calculateTotals();
        }
    }

    public function decreaseQuantity($productId): void
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] > 1) {
                $this->cart[$productId]['quantity']--;
                $this->cart[$productId]['total'] = $this->cart[$productId]['price'] * $this->cart[$productId]['quantity'];
                $this->calculateTotals();
            } else {
                $this->removeFromCart($productId);
            }
        }
    }

    public function removeFromCart($productId): void
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
            $this->calculateTotals();
        }
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->calculateTotals();
    }

    public function applyDiscount(): void
    {
        // Placeholder for actual discount logic
        if (strtoupper($this->discountCode) === 'SAVE10') {
            $this->discountAmount = $this->subtotal * 0.10;
            $this->dispatch('notify', ['type' => 'success', 'message' => '10% discount applied!']);
        } elseif (!empty($this->discountCode)) {
            $this->discountAmount = 0; // Reset if code is invalid
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Invalid discount code.']);
        } else {
            $this->discountAmount = 0; // Reset if code is cleared
        }
        $this->calculateTotals();
    }

    private function calculateTotals(): void
    {
        $this->subtotal = collect($this->cart)->sum('total');
        $this->discount = $this->discountAmount;
        $this->total = $this->subtotal - $this->discount;
    }

    public function openPaymentModal(string $method): void
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Cart is empty.']);
            return;
        }
        $this->paymentModalMethod = $method;
        $this->amountTendered = $this->total; // Pre-fill with total
        $this->calculateChange();
        $this->showPaymentModal = true;
    }
    
    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->paymentModalMethod = null;
        $this->amountTendered = 0;
        $this->changeDue = 0;
    }
    
    // Updated hook for amount tendered changes
    public function updatedAmountTendered(): void
    {
        $this->calculateChange();
    }

    public function calculateChange(): void
    {
        $tendered = (float) $this->amountTendered;
        if ($tendered >= $this->total) {
            $this->changeDue = $tendered - $this->total;
        } else {
            $this->changeDue = 0;
        }
    }
    
    public function completePayment(): void
    {
        if($this->paymentModalMethod === 'cash') {
             if((float)$this->amountTendered < $this->total) {
                 $this->dispatch('notify', ['type' => 'error', 'message' => 'Amount tendered is less than total.']);
                 return;
             }
        }
        
        $this->processOrder($this->paymentModalMethod);
        $this->closePaymentModal();
    }

    private function processOrder(string $paymentMethod): void
    {
        try {
            $paymentMethodEnum = match ($paymentMethod) {
                'card' => PaymentMethod::CreditCard, // Or specific card type if needed
                'mobile' => PaymentMethod::MobileMoney, // Or GCash/Paymaya etc.
                default => PaymentMethod::Cash,
            };
            
            $this->currentOrder = Order::create([
                'user_id' => Auth::id(), // Will be null if guest
                'reference' => null,
                'customer_name' => $this->customerName ?: 'Walk-in Customer',
                'customer_phone' => $this->customerPhone ?: null,
                'total_amount' => $this->total,
                'discount_amount' => $this->discountAmount,
                'tax_amount' => $this->taxAmount,
                'status' => OrderStatus::Confirmed,
                'payment_status' => PaymentStatus::Completed, // Assume immediate payment for POS
                'payment_method' => $paymentMethodEnum,
                'notes' => $this->orderNotes,
                'source' => $this->orderType, // Use selected order type as source
            ]);

            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $this->currentOrder->id,
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['total'],
                ]);
            }

            $this->receiptOrderData = [
                'reference' => $this->currentOrder->reference,
                'date' => now()->format('M d, Y'),
                'time' => now()->format('h:i A'),
                'items' => array_values($this->cart),
                'subtotal' => $this->subtotal,
                'tax' => $this->taxAmount,
                'discount' => $this->discountAmount,
                'total' => $this->total,
                'payment_method' => $paymentMethodEnum->label(),
                'amount_tendered' => $paymentMethod === 'cash' ? $this->amountTendered : null,
                'change_due' => $paymentMethod === 'cash' ? $this->changeDue : null,
                'cashier' => Auth::user()?->name ?? 'Cashier',
                'order_type' => Str::title(str_replace('-',' ',$this->orderType)),
            ];

            $this->showReceiptModal = true;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Order #' . $this->currentOrder->reference . ' processed!']);
            $this->clearCart(); // Clear cart AFTER successful processing

        } catch (\Exception $e) {
            Log::error("POS Order Processing Failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Order processing failed. Please check logs.']);
        }
    }

    public function printReceipt(): void
    {
        // In a real app, JS would handle the actual printing via browser API
        $this->dispatch('print-receipt', $this->receiptOrderData); // Pass data if needed by JS
        $this->closeReceiptModal();
    }

    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->receiptOrderData = []; // Reset receipt data
    }

    public function showOrderHistory(): void
    {
        $this->dispatch('show-modal', 'order-history');
    }

    public function showSettings(): void
    {
        $this->dispatch('show-modal', 'settings');
    }

    public function toggleFilter(): void
    {
        $this->showFilter = !$this->showFilter;
    }

    public function togglePopular(): void
    {
        $this->showPopular = !$this->showPopular;
        $this->loadProducts();
    }

    public function setOrderType(string $type): void
    {
        $this->orderType = $type;
    }

    public function printOrder(): void
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Cart is empty.']);
            return;
        }

        $this->dispatch('print-receipt', [
            'order' => $this->getOrderData()
        ]);
    }

    private function getOrderData(): array
    {
        return [
            'customer' => [
                'name' => $this->customerName,
                'phone' => $this->customerPhone,
                'email' => $this->customerEmail
            ],
            'items' => $this->cart,
            'totals' => [
                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'total' => $this->total
            ],
            'notes' => $this->orderNotes,
            'type' => $this->orderType
        ];
    }

    public function saveOrder(): void
    {
        if (empty($this->cart)) {
            return;
        }

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $this->customerName,
                'customer_phone' => $this->customerPhone,
                'customer_email' => $this->customerEmail,
                'order_type' => $this->orderType,
                'notes' => $this->orderNotes,
                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'total' => $this->total,
                'status' => 'pending'
            ]);

            foreach ($this->cart as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total']
                ]);
            }

            $this->clearCart();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Order saved successfully.']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to save order.']);
        }
    }

    public function loadOrder(string $orderId): void
    {
        try {
            $order = Order::with('items.product')->findOrFail($orderId);
            
            $this->cart = [];
            foreach ($order->items as $item) {
                $this->cart[$item->product_id] = [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->total
                ];
            }

            $this->customerName = $order->customer_name;
            $this->customerPhone = $order->customer_phone;
            $this->customerEmail = $order->customer_email;
            $this->orderType = $order->order_type;
            $this->orderNotes = $order->notes;
            $this->subtotal = $order->subtotal;
            $this->discount = $order->discount;
            $this->total = $order->total;

            $this->calculateTotals();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Order loaded successfully.']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to load order.']);
        }
    }

    public function render()
    {
        // Eager load category for products if active category is set
        if (!empty($this->activeCategory)) {
            $this->loadProducts(); // Reload products to ensure they match the active category
        }
        return view('livewire.pos', [
            'categories' => $this->categories,
            'products' => $this->products,
            'cart' => $this->cart,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'total' => $this->total
        ]);
    }
}
