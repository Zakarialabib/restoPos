<div>
    <div class="flex flex-col h-screen">
        <!-- Main POS Area -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Menu Section (Left 70%) -->
            <div class="w-3/4 flex flex-col bg-white">
                <!-- Order Type Selection -->
                <div class="px-4 py-2 border-b border-gray-200">
                    <div class="flex space-x-2">
                        <button wire:click="setOrderType('dine-in')"
                            class="flex-1 px-4 py-2 rounded-lg {{ $orderType === 'dine-in' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                            <span class="material-icons mr-2">restaurant</span>
                            Dine-In
                        </button>
                        <button wire:click="setOrderType('delivery')"
                            class="flex-1 px-4 py-2 rounded-lg {{ $orderType === 'delivery' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                            <span class="material-icons mr-2">delivery_dining</span>
                            Delivery
                        </button>
                        <button wire:click="setOrderType('takeout')"
                            class="flex-1 px-4 py-2 rounded-lg {{ $orderType === 'takeout' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                            <span class="material-icons mr-2">takeout_dining</span>
                            Takeout
                        </button>
                    </div>
                </div>

                <!-- Category Selection -->
                <div class="px-4 py-2 border-b border-gray-200">
                    <div class="flex space-x-2 overflow-x-auto pb-2">
                        <button wire:click="setActiveCategory('all')"
                            class="px-4 py-2 rounded-lg whitespace-nowrap {{ $selectedCategory === 'all' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                            All
                        </button>
                        @foreach ($categories as $category)
                            <button wire:click="setActiveCategory('{{ $category->id }}')"
                                class="px-4 py-2 rounded-lg whitespace-nowrap {{ $selectedCategory === $category->id ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="px-4 py-2 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="flex-1 relative">
                            <input type="text" wire:model.live="searchQuery" placeholder="Search products..."
                                class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <button wire:click="toggleFilter"
                            class="p-2 rounded-lg bg-orange-100 text-orange-600 hover:bg-orange-200">
                            <span class="material-icons">filter_list</span>
                        </button>
                        <button wire:click="togglePopular"
                            class="p-2 rounded-lg bg-orange-100 text-orange-600 hover:bg-orange-200">
                            <span class="material-icons">star</span>
                        </button>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="flex-1 px-4 py-2 overflow-y-auto bg-gray-50">

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($products as $product)
                            <div
                                class="bg-white rounded-xl shadow-sm overflow-hidden px-4 hover:shadow-md transition-all duration-300 border border-gray-100 hover:border-orange-200 group">
                                @if ($product->featured)
                                    <div
                                        class="absolute top-2 left-2 bg-orange-500 text-white px-2 py-1 rounded-lg text-xs font-medium flex items-center">
                                        <span class="material-icons text-sm mr-1">star</span>
                                        Featured
                                    </div>
                                @endif
                                <img src="https://images.unsplash.com/photo-1559847844-5315695dadae?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
                                    alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-xl">
                                <h3
                                    class="font-medium text-gray-800 group-hover:text-orange-600 transition-colors">
                                    {{ $product->name }}</h3>
                                <p class="text-orange-500 font-bold mb-3">
                                    {{ number_format($product->price, 2) }} Dh
                                </p>
                                <button wire:click="addToCart('{{ $product->id }}')" type="button"
                                    class=" py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                    <span class="material-icons">add_shopping_cart</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Section (Right 30%) -->
            <div class="w-1/3 flex flex-col bg-white border-l border-gray-200">
                <!-- Order Summary Header -->
                <div class="p-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="material-icons text-orange-500 mr-2">receipt_long</span>
                        <h2 class="text-lg font-semibold text-gray-800">Order Summary</h2>
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <span class="material-icons text-sm mr-1">schedule</span>
                        {{ now()->format('h:i A') }}
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="p-3 border-b border-gray-200">
                    <div class="space-y-2">
                        <div>
                            <input type="text" wire:model="customerName" placeholder="Customer Name"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        </div>
                        <div>
                            <input type="tel" wire:model="customerPhone" placeholder="Phone Number"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        </div>
                        <div>
                            <input type="email" wire:model="customerEmail" placeholder="Email"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="flex-1 p-3 overflow-y-auto">
                    @if (empty($cart))
                        <div class="flex flex-col items-center justify-center p-4 text-center">
                            <span class="material-icons text-4xl text-orange-200 mb-2">shopping_cart</span>
                            <h3 class="text-base font-medium text-gray-700 mb-1">Your cart is empty</h3>
                            <p class="text-xs text-gray-500">Add some items to get started</p>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach ($cart as $item)
                                <div
                                    class="flex items-center justify-between p-2 bg-white rounded-lg border border-gray-200">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex items-center space-x-1">
                                            <button wire:click="decreaseQuantity('{{ $item['id'] }}')"
                                                class="p-1 rounded-full bg-orange-100 text-orange-600 hover:bg-orange-200">
                                                <span class="material-icons text-sm">remove</span>
                                            </button>
                                            <span class="text-sm font-medium">{{ $item['quantity'] }}</span>
                                            <button wire:click="increaseQuantity('{{ $item['id'] }}')"
                                                class="p-1 rounded-full bg-orange-100 text-orange-600 hover:bg-orange-200">
                                                <span class="material-icons text-sm">add</span>
                                            </button>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-800">{{ $item['name'] }}</h4>
                                            <p class="text-xs text-gray-500">${{ number_format($item['price'], 2) }}
                                            </p>
                                        </div>
                                    </div>
                                    <button wire:click="removeFromCart('{{ $item['id'] }}')"
                                        class="p-1 rounded-full bg-red-100 text-red-600 hover:bg-red-200">
                                        <span class="material-icons text-sm">delete</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Order Totals -->
                <div class="p-3 border-t border-gray-200">
                    <div class="space-y-1">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <span class="material-icons text-gray-400 mr-2 text-sm">receipt</span>
                                <span class="text-sm text-gray-600">Subtotal</span>
                            </div>
                            <span class="text-sm text-gray-800">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <span class="material-icons text-gray-400 mr-2 text-sm">local_offer</span>
                                <span class="text-sm text-gray-600">Discount</span>
                            </div>
                            <span class="text-sm text-gray-800">${{ number_format($discount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <div class="flex items-center">
                                <span class="material-icons text-orange-500 mr-2 text-sm">payments</span>
                                <span class="text-sm font-medium text-gray-800">Total</span>
                            </div>
                            <span class="text-sm font-semibold text-orange-600">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Complete Order Button -->
                <div class="p-3 border-t border-gray-200">
                    <div class="grid grid-cols-3 gap-2">
                        <button wire:click="openPaymentModal('cash')"
                            class="w-full px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 font-medium">
                            <span class="material-icons mr-2">attach_money</span>
                            Cash
                        </button>
                        <button wire:click="openPaymentModal('card')"
                            class="w-full px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 font-medium">
                            <span class="material-icons mr-2">credit_card</span>
                            Card
                        </button>
                        <button wire:click="openPaymentModal('mobile')"
                            class="w-full px-4 py-2 rounded-lg bg-purple-500 text-white hover:bg-purple-600 font-medium">
                            <span class="material-icons mr-2">smartphone</span>
                            Mobile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payment Modal -->
            <div x-data x-show="$wire.showPaymentModal" @keydown.escape.window="$wire.closePaymentModal()"
                class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4" x-cloak>
                <div class="bg-white rounded-lg w-full max-w-md shadow-xl" @click.outside="$wire.closePaymentModal()">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold">Process Payment ({{ Str::title($paymentModalMethod ?? '') }})
                        </h3>
                        <button wire:click="closePaymentModal" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="mb-4 text-center">
                            <div class="text-gray-600 text-sm">Total Amount Due</div>
                            <div class="text-3xl font-bold text-yellow-600">${{ number_format($total, 2) ?? 0 }}</div>
                        </div>

                        @if ($paymentModalMethod === 'cash')
                            <div class="mb-4">
                                <label for="amountTendered" class="block text-gray-700 text-sm font-bold mb-2">Amount
                                    Tendered</label>
                                <input wire:model.live.debounce.150ms="amountTendered" id="amountTendered"
                                    type="number" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-yellow-500 text-right text-lg font-bold">
                            </div>

                            <div class="mb-4 p-3 bg-gray-100 rounded">
                                <div class="flex justify-between font-bold">
                                    <span>Change Due:</span>
                                    <span
                                        class="{{ $changeDue < 0 ? 'text-red-600' : '' }}">${{ number_format($changeDue, 2) ?? 0 }}</span>
                                </div>
                            </div>

                            {{-- Quick Cash Buttons --}}
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                @php $bills = [1, 5, 10, 20, 50, 100]; @endphp
                                @foreach ($bills as $value)
                                    <button
                                        wire:click="$set('amountTendered', {{ (float) $amountTendered + $value }})"
                                        class="bg-gray-200 hover:bg-gray-300 py-2 rounded">+${{ $value }}</button>
                                @endforeach
                                <button wire:click="$set('amountTendered', {{ $total }})"
                                    class="bg-yellow-200 hover:bg-yellow-300 py-2 rounded col-span-3 font-medium">Exact
                                    Amount</button>
                            </div>
                        @elseif($paymentModalMethod === 'card')
                            <div class="text-center py-8 text-gray-600">
                                <i class="fas fa-credit-card text-4xl mb-4"></i>
                                <p>Please process payment via card terminal.</p>
                                <p class="text-sm mt-2">(Click Complete when done)</p>
                            </div>
                        @elseif($paymentModalMethod === 'mobile')
                            <div class="text-center py-8 text-gray-600">
                                <i class="fas fa-mobile-alt text-4xl mb-4"></i>
                                <p>Please process mobile payment.</p>
                                <p class="text-sm mt-2">(Click Complete when done)</p>
                            </div>
                        @endif
                    </div>
                    <div class="p-4 border-t border-gray-200 flex justify-end space-x-2">
                        <button wire:click="closePaymentModal"
                            class="px-4 py-2 border border-gray-300 rounded-lg font-medium hover:bg-gray-100">
                            Cancel
                        </button>
                        <button wire:click="completePayment" wire:loading.attr="disabled"
                            wire:target="completePayment"
                            class="bg-green-500 hover:bg-green-600 disabled:opacity-50 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center"
                            @disabled($paymentModalMethod === 'cash' && (float) $amountTendered < $total)>
                            <span wire:loading.remove wire:target="completePayment">
                                <i class="fas fa-check mr-2"></i> Complete Payment
                            </span>
                            <span wire:loading wire:target="completePayment">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Receipt Modal -->
            <div x-data x-show="$wire.showReceiptModal" @keydown.escape.window="$wire.closeReceiptModal()"
                class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4" x-cloak>
                <div class="bg-white rounded-lg w-full max-w-sm shadow-xl" @click.outside="$wire.closeReceiptModal()">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold">Order Receipt</h3>
                        <button wire:click="closeReceiptModal" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @if (!empty($receiptOrderData))
                        <div class="p-4 receipt-print text-sm"
                            id="receipt-content-{{ $receiptOrderData['reference'] ?? 'new' }}">
                            <div class="text-center mb-4">
                                <h2 class="text-xl font-bold">{{ config('app.name', 'RESTAURANT') }}</h2>
                                <p>{{ config('app.address', '123 Main Street, Foodville') }}</p>
                                <p>Tel: {{ config('app.phone', '(555) 123-4567') }}</p>
                            </div>
                            <div class="border-t border-b border-dotted border-gray-400 py-1 my-2">
                                <div class="flex justify-between">
                                    <span>Order #{{ $receiptOrderData['reference'] ?? 'N/A' }}</span>
                                    <span>{{ $receiptOrderData['time'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Date: {{ $receiptOrderData['date'] ?? '' }}</span>
                                    <span>Cashier: {{ $receiptOrderData['cashier'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Type: {{ $receiptOrderData['order_type'] ?? 'N/A' }}</span>
                                    <span></span> {{-- Placeholder for alignment --}}
                                </div>
                            </div>
                            <div class="mb-2">
                                @foreach ($receiptOrderData['items'] ?? [] as $item)
                                    <div class="grid grid-cols-12 gap-1 py-0.5">
                                        <div class="col-span-1 text-right">{{ $item['quantity'] }}x</div>
                                        <div class="col-span-8">{{ $item['name'] }}</div>
                                        <div class="col-span-3 text-right">
                                            ${{ number_format($item['total'], 2) ?? 0 }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="border-t border-dotted border-gray-400 pt-1 mt-2">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($receiptOrderData['subtotal'] ?? 0, 2) }}</span>
                                </div>
                                @if (isset($receiptOrderData['discount']) && $receiptOrderData['discount'] > 0)
                                    <div class="flex justify-between">
                                        <span>Discount:</span>
                                        <span>-${{ number_format($receiptOrderData['discount'], 2) ?? 0 }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span>Tax ({{ number_format($taxRate * 100, 2) }}%):</span>
                                    <span>${{ number_format($receiptOrderData['tax'] ?? 0, 2) ?? 0 }}</span>
                                </div>
                                <div
                                    class="flex justify-between font-bold text-base border-t border-dotted border-gray-400 mt-1 pt-1">
                                    <span>Total:</span>
                                    <span>${{ number_format($receiptOrderData['total'] ?? 0, 2) ?? 0 }}</span>
                                </div>
                                <div class="mt-1 border-t border-dotted border-gray-400 pt-1">
                                    <div class="flex justify-between">
                                        <span>Payment Method:</span>
                                        <span>{{ $receiptOrderData['payment_method'] ?? 'N/A' }}</span>
                                    </div>
                                    @if (isset($receiptOrderData['amount_tendered']))
                                        <div class="flex justify-between">
                                            <span>Amount Tendered:</span>
                                            <span>${{ number_format($receiptOrderData['amount_tendered'], 2) ?? 0 }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Change Due:</span>
                                            <span>${{ number_format($receiptOrderData['change_due'] ?? 0, 2) ?? 0 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4 text-center text-xs">
                                <p>Thank you for dining with us!</p>
                                <p>Please come again</p>
                            </div>
                        </div>
                        <div class="p-4 border-t border-gray-200 flex justify-center space-x-4">
                            <button wire:click="printReceipt"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-print mr-2"></i> Print Receipt
                            </button>
                            {{-- <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-envelope mr-2"></i> Email Receipt
                </button> --}}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Livewire Notifications --}}
            {{-- Assumes you have a notification component setup --}}
            {{-- @livewire('utils.notifications') --}}

            <script>
                document.addEventListener('livewire:initialized', function() {
                    // Update current time display
                    const timeElement = document.getElementById('current-time');

                    function updateTime() {
                        if (timeElement) {
                            const now = new Date();
                            timeElement.textContent = now.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }
                    }
                    setInterval(updateTime, 60000);
                    updateTime(); // Initial call

                    // Print receipt listener
                    window.Livewire.on('print-receipt', (receiptData) => {
                        // Use optional chaining and nullish coalescing
                        const receiptRef = receiptData?.[0]?.reference ?? 'new';
                        const receiptId = `receipt-content-${receiptRef}`;
                        const content = document.getElementById(receiptId);

                        if (!content) {
                            console.error(`Receipt content element with ID '${receiptId}' not found for printing.`);
                            // Attempt to find any receipt content if specific one fails
                            const genericContent = document.querySelector('[id^="receipt-content-"]');
                            if (!genericContent) {
                                console.error('No receipt content found at all.');
                                alert('Could not find receipt content to print.');
                                return;
                            }
                            console.warn('Using generic receipt content as fallback.');
                            content = genericContent;
                        }

                        const printWindow = window.open('', '_blank', 'height=600,width=400');
                        printWindow.document.write('<html><head><title>Print Receipt</title>');
                        // Basic styling for thermal printers
                        printWindow.document.write(`
                    <style>
                        @page { size: 80mm auto; margin: 5mm; }
                        body { font-family: 'Courier New', monospace; font-size: 10pt; margin: 0; padding: 0; }
                        * { box-sizing: border-box; }
                        .text-center { text-align: center; }
                        .border-t, .border-b { border-top: 1px dotted #000; border-bottom: 1px dotted #000; }
                        .border-dotted { border-style: dotted; }
                        .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
                        .my-2 { margin-top: 0.5rem; margin-bottom: 0.5rem; }
                        .mb-4 { margin-bottom: 1rem; }
                        .mb-2 { margin-bottom: 0.5rem; }
                        .mt-1, .pt-1 { margin-top: 0.25rem; padding-top: 0.25rem; }
                        .mt-2 { margin-top: 0.5rem; }
                        .mt-4 { margin-top: 1rem; }
                        .text-xl { font-size: 1.25rem; }
                        .text-base { font-size: 1rem; }
                        .text-sm { font-size: 0.875rem; }
                        .text-xs { font-size: 0.75rem; }
                        .font-bold { font-weight: bold; }
                        .grid { display: grid; }
                        .grid-cols-12 { grid-template-columns: repeat(12, minmax(0, 1fr)); }
                        .col-span-1 { grid-column: span 1 / span 1; }
                        .col-span-3 { grid-column: span 3 / span 3; }
                        .col-span-8 { grid-column: span 8 / span 8; }
                        .gap-1 { gap: 0.25rem; }
                        .flex { display: flex; }
                        .justify-between { justify-content: space-between; }
                        .text-right { text-align: right; }
                    </style>
                `);
                        printWindow.document.write('</head><body>');
                        printWindow.document.write(content.innerHTML);
                        printWindow.document.write('</body></html>');
                        printWindow.document.close();

                        // Use timeout to ensure content is loaded before printing
                        setTimeout(() => {
                            printWindow.focus(); // Focus on the new window (helps some browsers)
                            printWindow.print();
                            // Don't close immediately, let the user handle the print dialog
                            // setTimeout(() => { printWindow.close(); }, 1000);
                        }, 250);
                    });
                });
            </script>
        </div>
    </div>

</div>
