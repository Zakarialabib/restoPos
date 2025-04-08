<div>
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    {{ config('app.name', 'RestoPos') }} Installation
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    Step {{ $currentStep }} of {{ $totalSteps }}
                </p>
            </div>

            <div class="mt-8">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
                </div>
            </div>

            <div class="mt-8">
                @if($currentStep === 1)
                    @include('livewire.installation.steps.company-details')
                @elseif($currentStep === 2)
                    @include('livewire.installation.steps.site-settings')
                @elseif($currentStep === 3)
                    @include('livewire.installation.steps.admin-user')
                @elseif($currentStep === 4)
                    @include('livewire.installation.steps.finish')
                @endif
            </div>

            <div class="mt-8 flex justify-between">
                @if($currentStep > 1)
                    <button wire:click="previousStep" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Previous
                    </button>
                @else
                    <div></div>
                @endif

                @if($currentStep < $totalSteps)
                    <button wire:click="nextStep" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Next
                    </button>
                @else
                    <button wire:click="completeInstallation" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Complete Installation
                    </button>
                @endif
            </div>
        </div>
    </div>
</div> 