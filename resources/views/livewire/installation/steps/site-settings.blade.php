<div>
    <h2 class="text-xl font-semibold mb-4">Site Settings</h2>
    <p class="text-gray-600 mb-6">Configure the basic settings for your website.</p>

    <div class="space-y-6">
        <div>
            <label for="site_logo" class="block text-sm font-medium text-gray-700">Site Logo</label>
            <div class="mt-1 flex items-center space-x-4">
                @if ($site_logo && !is_string($site_logo))
                    <div class="flex items-center">
                        <img src="{{ $site_logo->temporaryUrl() }}" class="h-16 w-auto object-contain">
                    </div>
                @elseif(is_string($site_logo) && $site_logo)
                    <div class="flex items-center">
                        <img src="{{ Storage::url($site_logo) }}" class="h-16 w-auto object-contain">
                    </div>
                @endif
                <label class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                    <span>Upload a file</span>
                    <input type="file" wire:model="site_logo" class="sr-only">
                </label>
            </div>
            @error('site_logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Homepage Type</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border rounded-lg p-4 cursor-pointer transition-colors duration-200 ease-in-out {{ $homepage_type === 'welcome' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:bg-gray-50' }}" wire:click="$set('homepage_type', 'welcome')">
                    <div class="font-medium">Welcome Page</div>
                    <div class="text-sm text-gray-500 mt-1">A standard presentation page for your business</div>
                </div>
                
                <div class="border rounded-lg p-4 cursor-pointer transition-colors duration-200 ease-in-out {{ $homepage_type === 'shop' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:bg-gray-50' }}" wire:click="$set('homepage_type', 'shop')">
                    <div class="font-medium">Shop</div>
                    <div class="text-sm text-gray-500 mt-1">E-commerce shop as your homepage</div>
                </div>
            </div>
            @error('homepage_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="flex items-center">
                <input id="multi_language" type="checkbox" wire:model="multi_language" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="multi_language" class="ml-2 block text-sm text-gray-700">Enable Multiple Languages</label>
            </div>
            <p class="text-sm text-gray-500 mt-1">Allow users to switch between different languages on your site</p>
        </div>
    </div>
</div> 