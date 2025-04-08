@props(['chartId', 'type', 'data', 'options', 'realtimeEnabled' => false])
<div class="h-64 relative" wire:ignore>
    <canvas id="{{ $chartId }}"></canvas>
    
    <div x-show="realtimeEnabled" 
         class="absolute top-2 right-2 flex items-center">
        <span class="flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
        </span>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
            const chart = new Chart(ctx, {
                type: '{{ $type }}',
                data: @json($data),
                options: @json($options)
            });

            Livewire.on('updateChart:{{ $chartId }}', (newData) => {
                chart.data = newData;
                chart.update('active');
            });
        });
    </script>
</div> 