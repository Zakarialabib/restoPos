<div>
    <!-- Create Modal -->
    <x-modal wire:model="createModal" name="create-cash-register">
        <x-slot name="title">
            {{ __('Create Cash Register') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit="create">
                <div>
                    <div class="my-4">
                        <x-label for="cash_in_hand" :value="__('Cash in Hand')" />
                        <x-input id="cash_in_hand" class="block mt-1 w-full" type="text" name="cash_in_hand" autofocus
                            wire:model="cash_in_hand" />
                        <x-input-error :messages="$errors->get('cash_in_hand')" for="cash_in_hand" class="mt-2" />
                    </div>

                    <div class="w-full">
                        <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Create Modal -->
</div>
