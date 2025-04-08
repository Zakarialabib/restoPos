<div>
    <!-- Floating Track Order Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <button wire:click="toggleExpand" class="floating-button bg-gradient-to-r from-orange-500 to-orange-600 text-white px-5 py-3 rounded-full shadow-lg flex items-center space-x-2 hover:from-orange-600 hover:to-orange-700 transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <span class="font-medium">Track Order</span>
        </button>
    </div>

    @if($isExpanded)
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Order Header -->
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-6 border-b border-orange-200">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-orange-500 rounded-full text-white shadow-md">
                                <i class="fas fa-receipt text-lg"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-gray-800 text-xl">
                                    @if($selectedOrder)
                                        Order #{{ $selectedOrder['id'] }}
                                    @elseif($hasPendingOrder)
                                        Order #{{ $pendingOrder['id'] ?? 'N/A' }}
                                    @else
                                        Order History
                                    @endif
                                </h2>
                                <p class="text-sm text-gray-600 mt-1" @if($selectedOrder || $hasPendingOrder)>
                                    <span class="font-medium">Placed:</span> 
                                    {{ \Carbon\Carbon::parse($selectedOrder['created_at'] ?? $pendingOrder['created_at'])->format('M d, Y h:i A') }}
                                @endif
                                </p>
                            </div>
                        </div>
                        <button wire:click="toggleExpand" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="flex-1 overflow-y-auto">
                    @if($showVerificationForm)
                        <!-- Verification Form -->
                        <div class="p-8">
                            <div class="max-w-md mx-auto">
                                <div class="text-center mb-8">
                                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6 text-orange-500">
                                        <i class="fas fa-envelope text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800">Verify Your Email</h3>
                                    <p class="text-gray-600 mt-2">Please enter the verification code sent to your email</p>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <div class="flex justify-center space-x-2">
                                            <input type="text" wire:model="verificationCode" placeholder="Enter 6-digit code" 
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-center text-xl tracking-widest">
                                        </div>
                                        <p class="text-center text-sm text-gray-500 mt-3">
                                            Didn't receive the code? <a href="#" class="text-orange-600 hover:text-orange-700 font-medium">Resend</a>
                                        </p>
                                    </div>
                                    <button wire:click="verifyCode" class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg">
                                        Verify Code
                                    </button>
                                    <button wire:click="toggleVerificationForm" class="w-full bg-white border border-gray-300 text-gray-700 px-4 py-3 rounded-lg font-medium transition-colors hover:bg-gray-50">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif($showLoginForm)
                        <!-- User Information Form -->
                        <div class="p-8">
                            <div class="max-w-md mx-auto">
                                <div class="text-center mb-8">
                                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6 text-orange-500">
                                        <i class="fas fa-user-circle text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800">Enter Your Information</h3>
                                    <p class="text-gray-600 mt-2">We'll use this to create your account</p>
                                </div>
                                <form wire:submit.prevent="saveUserInfo" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                        <input type="text" wire:model="name" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" wire:model="email" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                        <input type="tel" wire:model="phone" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="pt-4">
                                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg">
                                            Save & Continue
                                        </button>
                                        <button type="button" wire:click="toggleLoginForm" class="w-full mt-3 bg-white border border-gray-300 text-gray-700 px-4 py-3 rounded-lg font-medium transition-colors hover:bg-gray-50">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Order Details -->
                        <div class="p-6">
                            @if($selectedOrder || $hasPendingOrder)
                                @php
                                    $order = $selectedOrder ?? $pendingOrder;
                                @endphp

                                <!-- Order Status Timeline -->
                                <div class="mb-10 px-4">
                                    <h3 class="font-bold text-gray-800 mb-6 flex items-center">
                                        <i class="fas fa-truck text-orange-500 mr-2"></i>
                                        Order Status
                                    </h3>
                                    <div class="order-status flex justify-between items-start relative px-4">
                                        <!-- Step 1: Order Placed -->
                                        <div class="status-step completed text-center w-1/4">
                                            <div class="status-icon w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-3 shadow-md bg-green-500 text-white">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                            <p class="text-sm font-medium text-gray-800">Order Placed</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order['created_at'])->format('h:i A') }}</p>
                                        </div>
                                        
                                        <!-- Step 2: Preparing -->
                                        <div class="status-step text-center w-1/4 {{ $order['status'] === 'preparing' ? 'active' : ($order['status'] === 'on_the_way' || $order['status'] === 'completed' ? 'completed' : 'pending') }}">
                                            <div class="status-icon w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                                                <i class="fas fa-utensils"></i>
                                            </div>
                                            <p class="text-sm font-medium {{ $order['status'] === 'preparing' || $order['status'] === 'on_the_way' || $order['status'] === 'completed' ? 'text-gray-800' : 'text-gray-500' }}">Preparing</p>
                                            @if($order['status'] === 'preparing' || $order['status'] === 'on_the_way' || $order['status'] === 'completed')
                                                <p class="text-xs text-gray-500 mt-1">Est. 15 min</p>
                                            @endif
                                        </div>
                                        
                                        <!-- Step 3: On the Way -->
                                        <div class="status-step text-center w-1/4 {{ $order['status'] === 'on_the_way' ? 'active' : ($order['status'] === 'completed' ? 'completed' : 'pending') }}">
                                            <div class="status-icon w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                                                <i class="fas fa-motorcycle"></i>
                                            </div>
                                            <p class="text-sm font-medium {{ $order['status'] === 'on_the_way' || $order['status'] === 'completed' ? 'text-gray-800' : 'text-gray-500' }}">On the Way</p>
                                            @if($order['status'] === 'on_the_way' || $order['status'] === 'completed')
                                                <p class="text-xs text-gray-500 mt-1">Est. arrival {{ $estimatedTime }}</p>
                                            @endif
                                        </div>
                                        
                                        <!-- Step 4: Delivered -->
                                        <div class="status-step text-center w-1/4 {{ $order['status'] === 'completed' ? 'completed' : 'pending' }}">
                                            <div class="status-icon w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <p class="text-sm font-medium {{ $order['status'] === 'completed' ? 'text-gray-800' : 'text-gray-500' }}">Delivered</p>
                                            @if($order['status'] === 'completed')
                                                <p class="text-xs text-gray-500 mt-1">Completed</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($deliveryAddress)
                                    <!-- Delivery Details -->
                                    <div class="bg-orange-50 rounded-xl p-5 mb-6 border border-orange-100">
                                        <div class="flex items-start">
                                            <div class="bg-orange-100 rounded-full p-3 text-orange-600 mr-4">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-800">Delivery Address</h4>
                                                <p class="text-gray-600 text-sm mt-1">{{ $deliveryAddress }}</p>
                                                
                                                @if($driverName)
                                                    <div class="mt-4 flex items-center">
                                                        <div class="w-10 h-10 bg-gray-200 rounded-full overflow-hidden mr-3">
                                                            <img src="{{ $driverImage }}" alt="Driver" class="w-full h-full object-cover">
                                                        </div>
                                                        <div>
                                                            <h5 class="font-medium text-gray-800">{{ $driverName }}</h5>
                                                            <p class="text-gray-500 text-xs">Your Delivery Driver</p>
                                                        </div>
                                                        <a href="tel:{{ $driverPhone }}" class="ml-auto bg-white text-orange-500 p-2 rounded-full border border-orange-200 hover:bg-orange-50 transition-colors">
                                                            <i class="fas fa-phone"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Order Summary -->
                                <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6 shadow-sm">
                                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-list-alt text-orange-500 mr-2"></i>
                                        Order Summary
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        @foreach($order['items'] ?? [] as $item)
                                            <div class="flex justify-between items-start py-3 border-b border-gray-100 last:border-0">
                                                <div class="flex items-start space-x-3">
                                                    <div class="w-16 h-16 bg-orange-50 rounded-lg overflow-hidden flex-shrink-0">
                                                        <img src="{{ $item['product']['image_url'] ?? '' }}" 
                                                             alt="{{ $item['product']['name'] ?? '' }}" 
                                                             class="w-full h-full object-cover">
                                                    </div>
                                                    <div>
                                                        <h4 class="font-medium text-gray-800">{{ $item['product']['name'] ?? '' }}</h4>
                                                        <p class="text-xs text-gray-500 mt-1">{{ $item['notes'] ?? 'No special instructions' }}</p>
                                                        <div class="flex items-center mt-2">
                                                            <span class="text-sm text-gray-600">Qty: {{ $item['quantity'] ?? 0 }}</span>
                                                            @if($item['size'] ?? false)
                                                                <span class="text-sm text-gray-600 ml-2">Size: {{ $item['size'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="font-medium text-gray-800">${{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2) }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Order Totals -->
                                    <div class="mt-6 pt-4 border-t border-gray-200 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Subtotal</span>
                                            <span class="font-medium">${{ number_format($order['subtotal'] ?? 0, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Tax</span>
                                            <span class="font-medium">${{ number_format($order['tax'] ?? 0, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between font-bold mt-2 pt-2 border-t border-gray-200">
                                            <span>Total</span>
                                            <span class="text-orange-600">${{ number_format($order['total'] ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if(!Auth::check() && $hasPendingOrder)
                                    <!-- Track Order Button -->
                                    <div class="text-center">
                                        <button wire:click="toggleLoginForm" class="w-full max-w-md mx-auto bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg flex items-center justify-center">
                                            <i class="fas fa-bell mr-2"></i>
                                            Get Delivery Updates
                                        </button>
                                        <p class="text-sm text-gray-500 mt-2">We'll send you notifications about your order status</p>
                                    </div>
                                @endif
                            @elseif(count($userOrders) > 0)
                                <!-- User's Order History -->
                                <div class="space-y-4">
                                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-history text-orange-500 mr-2"></i>
                                        Order History
                                    </h3>
                                    @foreach($userOrders as $order)
                                        <div wire:click="selectOrder('{{ $order['id'] }}')" 
                                             class="bg-white rounded-xl border border-gray-200 p-4 cursor-pointer hover:border-orange-300 hover:shadow-md transition-all duration-200">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h4 class="font-medium text-gray-800">Order #{{ $order['id'] }}</h4>
                                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y h:i A') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-medium text-gray-800">${{ number_format($order['total'], 2) }}</p>
                                                    <span class="inline-block px-2 py-1 text-xs rounded-full mt-1
                                                        {{ $order['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                                                           ($order['status'] === 'preparing' ? 'bg-orange-100 text-orange-800' : 
                                                           ($order['status'] === 'on_the_way' ? 'bg-blue-100 text-blue-800' : 
                                                           'bg-gray-100 text-gray-800')) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $order['status'])) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- No Orders Found -->
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6 text-orange-500">
                                        <i class="fas fa-search text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800">No Orders Found</h3>
                                    <p class="text-gray-600 mt-2 max-w-md mx-auto">We couldn't find any orders for you. Start ordering delicious food to see your orders here!</p>
                                    <button class="mt-6 px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors shadow-md hover:shadow-lg">
                                        Browse Menu
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .order-status {
            position: relative;
        }
        .order-status::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e5e7eb;
            z-index: 0;
        }
        .status-step {
            position: relative;
            z-index: 1;
        }
        .status-icon {
            transition: all 0.3s ease;
        }
        .status-step.active .status-icon {
            background-color: #f97316;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.2);
        }
        .status-step.completed .status-icon {
            background-color: #10b981;
            color: white;
        }
        .status-step.pending .status-icon {
            background-color: #f3f4f6;
            color: #9ca3af;
        }
        .status-step.completed .status-line {
            background-color: #10b981;
        }
        .status-step.active .status-line-half {
            background-color: #10b981;
        }
        .floating-button {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .floating-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(249, 115, 22, 0.4);
        }
        .modal-content {
            animation: slideUp 0.3s ease-out forwards;
        }
        @keyframes slideUp {
            0% {
                transform: translateY(50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</div>
