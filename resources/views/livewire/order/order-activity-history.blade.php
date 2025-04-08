<div>
    <div class="bg-white rounded-lg shadow" x-data="{ showFilters: false }">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Order Activity History
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Track all changes and activities related to this order
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click="showFilters = !showFilters"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filters
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showFilters" class="border-t border-gray-200 px-4 py-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label for="action-filter" class="block text-sm font-medium text-gray-700">Action</label>
                    <select 
                        id="action-filter" 
                        wire:model.live="actionFilter"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    >
                        <option value="">All Actions</option>
                        <option value="created">Created</option>
                        <option value="updated">Updated</option>
                        <option value="status_changed">Status Changed</option>
                    </select>
                </div>
                <div>
                    <label for="date-range" class="block text-sm font-medium text-gray-700">Date Range</label>
                    <input 
                        type="date" 
                        id="date-range" 
                        wire:model.live="dateFilter"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                </div>
                <div>
                    <label for="user-filter" class="block text-sm font-medium text-gray-700">User</label>
                    <select 
                        id="user-filter" 
                        wire:model.live="userFilter"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    >
                        <option value="">All Users</option>
                        @foreach($this->users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button 
                    wire:click="resetFilters"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Reset Filters
                </button>
            </div>
        </div>

        <div class="border-t border-gray-200">
            <div>
                <div class="bg-white rounded-lg shadow">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Order Activity History
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Track all changes and activities related to this order
                        </p>
                    </div>

                    <div class="border-t border-gray-200">
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @forelse($activities as $activity)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                        @switch($activity->action)
                                                            @case('created')
                                                                <svg class="h-5 w-5 text-green-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                            @break

                                                            @case('updated')
                                                                <svg class="h-5 w-5 text-blue-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                    </path>
                                                                </svg>
                                                            @break

                                                            @case('status_changed')
                                                                <svg class="h-5 w-5 text-yellow-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                                    </path>
                                                                </svg>
                                                            @break

                                                            @default
                                                                <svg class="h-5 w-5 text-gray-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                    </path>
                                                                </svg>
                                                        @endswitch
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            {{ $activity->description }}
                                                            @if ($activity->causer)
                                                                <span class="font-medium text-gray-900">by
                                                                    {{ $activity->causer->name }}</span>
                                                            @endif
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $activity->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <button wire:click="toggleDetails('{{ $activity->id }}')"
                                                            class="text-blue-600 hover:text-blue-800">
                                                            {{ $selectedActivityId === $activity->id ? 'Hide Details' : 'Show Details' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    @if ($selectedActivityId === $activity->id)
                                        <li class="ml-12 mb-4">
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="space-y-2">
                                                    @if ($activity->changes)
                                                        <div class="text-sm">
                                                            <span class="font-medium text-gray-900">Changes:</span>
                                                            <ul class="mt-1 list-disc list-inside">
                                                                @foreach ($activity->changes as $field => $change)
                                                                    <li class="text-gray-600">
                                                                        {{ ucfirst(str_replace('_', ' ', $field)) }}:
                                                                        <span
                                                                            class="text-red-500">{{ $change['old'] ?? 'null' }}</span>
                                                                        →
                                                                        <span
                                                                            class="text-green-500">{{ $change['new'] ?? 'null' }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif

                                                    @if ($activity->properties)
                                                        <div class="text-sm">
                                                            <span class="font-medium text-gray-900">Additional
                                                                Properties:</span>
                                                            <ul class="mt-1 list-disc list-inside">
                                                                @foreach ($activity->properties as $key => $value)
                                                                    <li class="text-gray-600">
                                                                        {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                                                        @if (is_array($value))
                                                                            {{ json_encode($value) }}
                                                                        @else
                                                                            {{ $value }}
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif

                                                    @if ($activity->ip_address)
                                                        <div class="text-sm">
                                                            <span class="font-medium text-gray-900">IP Address:</span>
                                                            <p class="text-gray-600">{{ $activity->ip_address }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                    @empty
                                        <li class="text-center py-4 text-gray-500">
                                            No activity history found
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div class="px-4 py-3 sm:px-6">
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
