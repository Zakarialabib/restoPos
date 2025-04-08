<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CartDrawer extends Component
{
    public array $cartItems = [];
    public float $cartTotal = 0;

    protected $listeners = [
        'cartUpdated' => 'refreshCart',
        'addToCart' => 'addToCart',
        'removeFromCart' => 'removeFromCart',
        'updateQuantity' => 'updateQuantity',
        'clearCart' => 'clearCart',
        'editComposableProduct' => 'editComposableProduct'
    ];

    public function mount(): void
    {
        $this->refreshCart();
    }

    public function refreshCart(): void
    {
        $cart = Session::get('cart', ['items' => []]);
        $this->cartItems = $cart['items'] ?? [];
        $this->calculateTotal();
    }

    public function addToCart($product, $quantity = 1, $size = null): void
    {
        $cart = Session::get('cart', ['items' => []]);

        // Find if the product is already in the cart with the same size
        $existingItemIndex = array_search(
            true,
            array_map(fn ($item) => $item['id'] === $product['id'] && $item['size'] === $size, $cart['items'])
        );

        if (false !== $existingItemIndex) {
            // Update existing item quantity
            $cart['items'][$existingItemIndex]['quantity'] += $quantity;
            $cart['items'][$existingItemIndex]['total'] = $cart['items'][$existingItemIndex]['price'] * $cart['items'][$existingItemIndex]['quantity'];
        } else {
            // Add new item to cart
            $cart['items'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $size && isset($product['prices'])
                    ? collect($product['prices'])->firstWhere('size', $size)['price']
                    : $product['price'],
                'image' => $product['image'] ?? null,
                'quantity' => $quantity,
                'size' => $size,
                'total' => ($size && isset($product['prices'])
                    ? collect($product['prices'])->firstWhere('size', $size)['price']
                    : $product['price']) * $quantity,
                'options' => [],
                'addedAt' => now()->toIso8601String()
            ];
        }

        // Update cart items and session
        $this->cartItems = $cart['items'];
        $this->updateCart();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "{$product['name']} added to cart"
        ]);
    }

    public function removeFromCart($productId, $size = null): void
    {
        $cart = Session::get('cart', ['items' => []]);

        // Filter out the item to remove
        $cart['items'] = array_values(array_filter($cart['items'], fn ($item) => ! ($item['id'] === $productId && $item['size'] === $size)));

        // Update cart items and session
        $this->cartItems = $cart['items'];
        $this->updateCart();

        $this->dispatch('notify', [
            'type' => 'info',
            'message' => "Item removed from cart"
        ]);
    }

    public function updateQuantity($productId, $size, $quantity): void
    {
        if ($quantity < 1) {
            $this->removeFromCart($productId, $size);
            return;
        }

        $cart = Session::get('cart', ['items' => []]);

        foreach ($cart['items'] as &$item) {
            if ($item['id'] === $productId && $item['size'] === $size) {
                $item['quantity'] = $quantity;
                $item['total'] = $item['price'] * $quantity;
                break;
            }
        }

        // Update cart items and session
        $this->cartItems = $cart['items'];
        $this->updateCart();

        $this->dispatch('notify', [
            'type' => 'info',
            'message' => "Cart updated"
        ]);
    }

    public function clearCart(): void
    {
        // Clear cart items and update session
        $this->cartItems = [];
        $this->updateCart();

        $this->dispatch('notify', [
            'type' => 'info',
            'message' => "Cart cleared"
        ]);
    }

    public function placeOrder(): void
    {
        if (empty($this->cartItems)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => "Your cart is empty"
            ]);
            return;
        }

        try {
            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $this->cartTotal,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($this->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                ]);
            }

            // Save order to session for tracking
            Session::put('pendingOrder', [
                'id' => $order->id,
                'total' => $this->cartTotal,
                'items' => $this->cartItems,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'status' => 'pending'
            ]);

            // Clear the cart
            $this->cartItems = [];
            $this->updateCart();

            // Show success message
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Order placed successfully!'
            ]);

            // Dispatch event to expand order track
            $this->dispatch('order-placed');

        } catch (Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => "Failed to place order. Please try again."
            ]);
        }
    }

    // For testing purposes only - remove in production
    public function simulateOrder(): void
    {
        // Generate a unique ID for the test order
        $orderId = 'TEST-' . mb_strtoupper(mb_substr(md5(uniqid()), 0, 8));

        // Save order to session for tracking
        Session::put('pendingOrder', [
            'id' => $orderId,
            'total' => $this->cartTotal,
            'items' => $this->cartItems,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        // Clear the cart
        $this->cartItems = [];
        $this->updateCart();

        // Show success message
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Test order created successfully!'
        ]);

        // Dispatch event to expand order track
        $this->dispatch('order-placed');
    }

    /**
     * Update the cart in the session
     */
    public function updateCart(): void
    {
        Session::put('cart', [
            'items' => $this->cartItems
        ]);
        $this->calculateTotal();
    }

    /**
     * Edit a composable product in the cart
     * This will redirect to the composable product page with the product details
     */
    public function editComposableProduct($index)
    {
        if ( ! isset($this->cartItems[$index]) || ! isset($this->cartItems[$index]['is_composable']) || ! $this->cartItems[$index]['is_composable']) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('This product cannot be edited')
            ]);
            return;
        }

        $item = $this->cartItems[$index];
        $productType = $item['product_type'] ?? 'juices';

        // Store the item being edited in the session
        session()->put('editing_composable', [
            'index' => $index,
            'item' => $item
        ]);

        // Redirect to the composable product page
        return redirect()->route('composable.product', ['productType' => $productType]);
    }

    /**
     * Add a composable product to the cart
     */
    public function addComposableToCart($product): void
    {
        $cart = Session::get('cart', ['items' => []]);

        // Check if we're editing an existing item
        $editingComposable = session('editing_composable');

        if ($editingComposable) {
            // Replace the existing item
            $index = $editingComposable['index'];
            $originalItem = $editingComposable['item'];

            // Preserve the original quantity
            $product['quantity'] = $originalItem['quantity'] ?? 1;
            $product['total'] = $product['price'] * $product['quantity'];

            $cart['items'][$index] = $product;

            // Clear the editing session
            session()->forget('editing_composable');

            $message = __('Product updated in cart');
        } else {
            // Add as a new item
            $cart['items'][] = $product;
            $message = __('Product added to cart');
        }

        // Update cart items and session
        $this->cartItems = $cart['items'];
        $this->updateCart();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    public function render()
    {
        return view('livewire.cart-drawer');
    }

    private function calculateTotal(): void
    {
        $this->cartTotal = array_reduce($this->cartItems, fn ($total, $item) => $total + ($item['total'] ?? ((float) ($item['price']) * $item['quantity'])), 0);
    }
}
