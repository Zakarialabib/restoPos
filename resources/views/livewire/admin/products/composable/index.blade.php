<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Composable Configurations</h2>
        <button wire:click="create" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            Create Configuration
        </button>
    </div>

    <!-- Search and Filters -->
    <div class="mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Search configurations..." 
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <!-- Configurations Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                        Name
                        @if($sortField === 'name')
                            @if($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Category
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Features
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($configurations as $config)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $config->name }}</div>
                            <div class="text-sm text-gray-500">{{ $config->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $config->category->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @if($config->has_base)
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">Base</span>
                                @endif
                                @if($config->has_sugar)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Sugar</span>
                                @endif
                                @if($config->has_size)
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Size</span>
                                @endif
                                @if($config->has_addons)
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded">Add-ons</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="toggleStatus({{ $config->id }})" 
                                class="px-2 py-1 text-xs font-medium rounded-full {{ $config->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $config->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $config->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <button wire:click="delete({{ $config->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No configurations found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $configurations->links() }}
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ $editing ? 'Edit Configuration' : 'Create Configuration' }}
                </h3>

                <form wire:submit="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select wire:model="form.category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('form.category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('form.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('form.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="form.has_base" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Has Base</span>
                                </label>
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="form.has_sugar" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Has Sugar</span>
                                </label>
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="form.has_size" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Has Size</span>
                                </label>
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="form.has_addons" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Has Add-ons</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Min Ingredients</label>
                                <input type="number" wire:model="form.min_ingredients" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('form.min_ingredients') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Max Ingredients</label>
                                <input type="number" wire:model="form.max_ingredients" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('form.max_ingredients') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        @if($form['has_size'])
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sizes</label>
                                <div class="mt-2 space-y-2">
                                    @foreach($form['sizes'] as $index => $size)
                                        <div class="flex items-center space-x-2">
                                            <input type="text" wire:model="form.sizes.{{ $index }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <button type="button" wire:click="removeSize({{ $index }})" class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                    <button type="button" wire:click="addSize" class="text-indigo-600 hover:text-indigo-900 text-sm">+ Add Size</button>
                                </div>
                                @error('form.sizes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($form['has_base'])
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Base Types</label>
                                <div class="mt-2 space-y-2">
                                    @foreach($form['base_types'] as $index => $type)
                                        <div class="flex items-center space-x-2">
                                            <input type="text" wire:model="form.base_types.{{ $index }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <button type="button" wire:click="removeBaseType({{ $index }})" class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                    <button type="button" wire:click="addBaseType" class="text-indigo-600 hover:text-indigo-900 text-sm">+ Add Base Type</button>
                                </div>
                                @error('form.base_types') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($form['has_sugar'])
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sugar Types</label>
                                <div class="mt-2 space-y-2">
                                    @foreach($form['sugar_types'] as $index => $type)
                                        <div class="flex items-center space-x-2">
                                            <input type="text" wire:model="form.sugar_types.{{ $index }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <button type="button" wire:click="removeSugarType({{ $index }})" class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                    <button type="button" wire:click="addSugarType" class="text-indigo-600 hover:text-indigo-900 text-sm">+ Add Sugar Type</button>
                                </div>
                                @error('form.sugar_types') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($form['has_addons'])
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Add-on Types</label>
                                <div class="mt-2 space-y-2">
                                    @foreach($form['addon_types'] as $index => $type)
                                        <div class="flex items-center space-x-2">
                                            <input type="text" wire:model="form.addon_types.{{ $index }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <button type="button" wire:click="removeAddonType({{ $index }})" class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                    <button type="button" wire:click="addAddonType" class="text-indigo-600 hover:text-indigo-900 text-sm">+ Add Add-on Type</button>
                                </div>
                                @error('form.addon_types') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="form.is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            {{ $editing ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
