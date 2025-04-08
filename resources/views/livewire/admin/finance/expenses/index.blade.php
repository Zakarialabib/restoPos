<div>

    <x-theme.breadcrumb :title="__('Expense Management')" :parent="route('admin.expenses')" :parentName="__('Expense Management')">
        <x-button wire:click="$set('showExpenseModal', true)" color="primary">
            {{ __('Add New Expense') }}
        </x-button>
    </x-theme.breadcrumb>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Total Expenses') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_expenses'], 2) }} DH
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Monthly Expenses') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['monthly_expenses'], 2) }} DH
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Categories') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ count($stats['categories']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <x-label for="search" :value="__('Search')" />
                <x-input wire:model.live="search" type="search" placeholder="{{ __('Search expenses...') }}"
                    class="w-full" />
            </div>
            <div>
                <x-label for="category_filter" :value="__('Category')" />
                <select wire:model.live="category_filter" class="w-full form-select">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach ($stats['categories'] as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-label for="date_from" :value="__('From')" />
                    <x-input wire:model.live="date_from" type="date" class="w-full" />
                </div>
                <div>
                    <x-label for="date_to" :value="__('To')" />
                    <x-input wire:model.live="date_to" type="date" class="w-full" />
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Date') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Category') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Description') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Amount') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Payment Method') }}
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $expense->date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $expense->category }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $expense->description }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ number_format($expense->amount, 2) }} DH</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $expense->payment_method }}</div>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-2">
                                    <x-button wire:click="editExpense('{{ $expense->id }}')" color="primary"
                                        size="sm">
                                        <span class="material-icons">edit</span>
                                    </x-button>
                                    <x-button wire:click="$set('showDeleteModal', true)" color="danger" size="sm">
                                        <span class="material-icons">delete</span>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                {{ __('No expenses found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $expenses->links() }}
        </div>
    </div>


    <!-- Expense Modal -->
    <x-modal wire:model="showExpenseModal" name="showExpenseModal">
        <x-slot name="title">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                {{ $selectedExpense ? __('Edit Expense') : __('Add New Expense') }}
            </h3>
        </x-slot>
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="category" :value="__('Category')" />
                    <x-input wire:model="category" type="text" class="w-full" />
                    @error('category')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-label for="amount" :value="__('Amount')" />
                    <x-input wire:model="amount" type="number" step="0.01" class="w-full" />
                    @error('amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-label for="date" :value="__('Date')" />
                    <x-input wire:model="date" type="date" class="w-full" />
                    @error('date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-label for="payment_method" :value="__('Payment Method')" />
                    <x-input wire:model="payment_method" type="text" class="w-full" />
                    @error('payment_method')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-label for="reference_number" :value="__('Reference Number')" />
                    <x-input wire:model="reference_number" type="text" class="w-full" />
                    @error('reference_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-label for="attachments" :value="__('Attachments')" />
                    <input type="file" wire:model="attachments" multiple class="w-full" />
                    @error('attachments.*')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <x-label for="description" :value="__('Description')" />
                <x-textarea wire:model="description" class="w-full" rows="3" />
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <x-label for="notes" :value="__('Notes')" />
                <x-textarea wire:model="notes" class="w-full" rows="3" />
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <x-button wire:click="$set('showExpenseModal', false)" color="secondary" type="button">
                    {{ __('Cancel') }}
                </x-button>
                <x-button wire:click="{{ $selectedExpense ? 'updateExpense' : 'createExpense' }}" color="primary"
                    type="button">
                    {{ $selectedExpense ? __('Update') : __('Create') }}
                </x-button>
            </div>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="showDeleteModal" name="showDeleteModal">
        <x-slot name="title">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Delete Expense') }}</h3>
        </x-slot>
        <div class="space-y-4">
            <p class="text-gray-600">
                {{ __('Are you sure you want to delete this expense? This action cannot be undone.') }}</p>
            <div class="flex justify-end space-x-3">
                <x-button wire:click="$set('showDeleteModal', false)" color="secondary" type="button">
                    {{ __('Cancel') }}
                </x-button>
                <x-button wire:click="deleteExpense('{{ $selectedExpense }}')" color="danger" type="button">
                    {{ __('Delete') }}
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
