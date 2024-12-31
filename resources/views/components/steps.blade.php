@props(['steps', 'currentStep'])

<div class="w-full">
    <div class="flex justify-between">
        @foreach($steps as $index => $step)
            <div class="flex items-center">
                <div class="relative">
                    <!-- Step Circle -->
                    <div class="{{ $index + 1 <= $currentStep ? 'bg-indigo-600' : 'bg-gray-300' }} 
                                rounded-full h-10 w-10 flex items-center justify-center">
                        <i class="fas fa-{{ $step['icon'] }} text-white"></i>
                    </div>
                    
                    <!-- Step Title -->
                    <div class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 
                                whitespace-nowrap text-sm font-medium
                                {{ $index + 1 <= $currentStep ? 'text-indigo-600' : 'text-gray-500' }}">
                        {{ $step['title'] }}
                    </div>
                </div>
                
                <!-- Connector Line -->
                @if(!$loop->last)
                    <div class="flex-1 h-0.5 mx-4 {{ $index + 1 < $currentStep ? 'bg-indigo-600' : 'bg-gray-300' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div> 