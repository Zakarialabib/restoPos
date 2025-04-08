<div>
    <h2 class="text-xl font-semibold mb-4">Company Information</h2>
    <p class="text-gray-600 mb-6">Please enter your company details below.</p>

    <div class="space-y-6">
        <div>
            <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
            <input type="text" id="company_name" wire:model="company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('company_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="site_title" class="block text-sm font-medium text-gray-700">Site Title</label>
            <input type="text" id="site_title" wire:model="site_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('site_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="company_email_address" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" id="company_email_address" wire:model="company_email_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('company_email_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="company_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" id="company_phone" wire:model="company_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('company_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="company_address" class="block text-sm font-medium text-gray-700">Address</label>
            <textarea id="company_address" wire:model="company_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            @error('company_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="company_tax" class="block text-sm font-medium text-gray-700">Tax Number (Optional)</label>
            <input type="text" id="company_tax" wire:model="company_tax" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('company_tax') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>
</div> 