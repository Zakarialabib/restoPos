<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.guest')]
class OrderTrack extends Component
{
    public bool $isExpanded = false;
    public bool $showVerificationForm = false;
    public bool $showLoginForm = false;
    public string $verificationCode = '';
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public array $pendingOrder = [];
    public bool $hasPendingOrder = false;
    public array $userOrders = [];
    public ?array $selectedOrder = null;
    public ?string $deliveryAddress = null;
    public ?string $driverName = null;
    public ?string $driverPhone = null;
    public ?string $driverImage = null;
    public ?string $estimatedTime = null;

    public function mount(): void
    {
        // Check for pending order in session
        if (Session::has('pendingOrder')) {
            $this->pendingOrder = Session::get('pendingOrder');
            $this->hasPendingOrder = true;
            $this->loadDeliveryDetails();
        }

        // Load user's orders if authenticated
        if (Auth::check()) {
            $this->loadUserOrders();
        }
    }

    public function loadDeliveryDetails(): void
    {
        if ($this->hasPendingOrder) {
            // In a real app, this would fetch from your delivery service
            $this->deliveryAddress = '123 Main Street, Apt 4B, New York, NY 10001';
            $this->driverName = 'John D.';
            $this->driverPhone = '+1234567890';
            $this->driverImage = 'https://randomuser.me/api/portraits/men/32.jpg';
            $this->estimatedTime = now()->addMinutes(15)->format('h:i A');
        }
    }

    #[Computed]
    public function hasPendingOrder()
    {
        return Session::has('pendingOrder');
    }

    #[Computed]
    public function pendingOrder()
    {
        return Session::get('pendingOrder');
    }

    public function loadUserOrders(): void
    {
        if (Auth::check()) {
            $this->userOrders = Order::where('user_id', Auth::id())
                ->with(['items.product'])
                ->latest()
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'total' => $order->total,
                        'status' => $order->status,
                        'created_at' => $order->created_at,
                        'items' => $order->items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'name' => $item->name,
                                'price' => $item->price,
                                'quantity' => $item->quantity,
                                'size' => $item->size,
                                'notes' => $item->notes,
                                'product' => [
                                    'name' => $item->product->name ?? $item->name,
                                    'image_url' => $item->product->image_url ?? null,
                                ],
                            ];
                        })->toArray(),
                    ];
                })->toArray();
        }
    }

    public function selectOrder($orderId): void
    {
        $this->selectedOrder = collect($this->userOrders)->firstWhere('id', $orderId);
        $this->isExpanded = true;
    }

    public function toggleExpand(): void
    {
        $this->isExpanded = !$this->isExpanded;
        if (!$this->isExpanded) {
            $this->selectedOrder = null;
        }
    }

    public function toggleLoginForm(): void
    {
        $this->showLoginForm = !$this->showLoginForm;
        $this->showVerificationForm = false;
    }

    public function toggleVerificationForm(): void
    {
        $this->showVerificationForm = !$this->showVerificationForm;
        $this->showLoginForm = false;
    }

    #[On('order-placed')]
    public function handleOrderPlaced($orderData): void
    {
        $this->pendingOrder = $orderData;
        $this->hasPendingOrder = true;
        $this->isExpanded = true;
        $this->loadDeliveryDetails();
        
        // If user is logged in, reload their orders
        if (Auth::check()) {
            $this->loadUserOrders();
        }
    }

    public function saveUserInfo(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
        ]);

        // Generate verification code
        $verificationCode = Str::random(6);
        
        // Store user info and verification code in session
        Session::put('pendingOrderUserInfo', [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'verification_code' => $verificationCode,
        ]);

        // TODO: Send verification email
        // Mail::to($this->email)->send(new OrderVerificationMail($verificationCode));

        $this->showLoginForm = false;
        $this->showVerificationForm = true;
    }

    public function verifyCode(): void
    {
        $this->validate([
            'verificationCode' => 'required|size:6',
        ]);

        $userInfo = Session::get('pendingOrderUserInfo');
        
        if ($this->verificationCode === $userInfo['verification_code']) {
            // Create user account
            $user = User::create([
                'name' => $userInfo['name'],
                'email' => $userInfo['email'],
                'phone' => $userInfo['phone'],
                'password' => Hash::make(Str::random(10)), // Generate random password
                'email_verified_at' => now(),
            ]);

            // Associate pending order with user
            if ($this->hasPendingOrder) {
                $order = Order::find($this->pendingOrder['id']);
                if ($order) {
                    $order->user_id = $user->id;
                    $order->save();
                }
            }

            // Login the user
            Auth::login($user);

            // Load user's orders
            $this->loadUserOrders();

            // Clear session data
            Session::forget('pendingOrderUserInfo');
            Session::forget('verification_code');

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Account created successfully!'
            ]);

            // Redirect to dashboard
            $this->redirect(route('dashboard'));
        } else {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Invalid verification code!'
            ]);
        }
    }

    public function clearPendingOrder(): void
    {
        Session::forget('pendingOrder');
        $this->pendingOrder = [];
        $this->hasPendingOrder = false;
        $this->showVerificationForm = false;
        $this->showLoginForm = false;
        $this->deliveryAddress = null;
        $this->driverName = null;
        $this->driverPhone = null;
        $this->driverImage = null;
        $this->estimatedTime = null;

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Order tracking cleared!'
        ]);
    }

    public function render()
    {
        return view('livewire.order-track');
    }
}
