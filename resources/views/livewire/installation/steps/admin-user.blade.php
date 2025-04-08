<div>
    <h2 class="text-xl font-semibold mb-4">Admin Account</h2>
    <p class="text-gray-600 mb-6">Create the administrator account for your website.</p>

    <div class="space-y-4">
        <div>
            <label for="admin_email" class="block text-sm font-medium text-gray-700">Admin Email</label>
            <input type="email" id="admin_email" wire:model="admin_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('admin_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="admin_password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="admin_password" wire:model="admin_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('admin_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" id="admin_password_confirmation" wire:model="admin_password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>
</div> 