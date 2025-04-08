<div>

    <x-theme.breadcrumb :title="__('Waste Management')" :parent="route('admin.waste-management')" :parentName="__('Waste Management')">
        {{-- <x-button wire:click="$set('showWasteModal', true)" color="primary">
            {{ __('Add New Waste') }}
        </x-button> --}}
    </x-theme.breadcrumb>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <div class="mt-1">
                        <input type="text" wire:model.live="search" id="search"
                            class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Search ingredients...">
                    </div>
                </div>
                <div class="w-full sm:w-48">
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select wire:model.live="category" id="category"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                        <option value="">All Categories</option>
                        @foreach ($this->categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Ingredients List -->
    <div class="bg-white shadow rounded-lg">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox"
                                            class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Expiry Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($ingredients as $ingredient)
                                    @php
                                        $status = $this->getIngredientStatus($ingredient);
                                        $statusColor = $this->getStatusColor($status);
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" wire:model="selectedIngredients"
                                                value="{{ $ingredient->id }}"
                                                class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $ingredient->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $ingredient->category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $ingredient->quantity }} {{ $ingredient->unit }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $ingredient->expiry_date->format('Y-m-d') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $ingredient->expiry_date->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                                {{ $this->getStatusText($status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="#" class="text-orange-600 hover:text-orange-900">Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No ingredients found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
