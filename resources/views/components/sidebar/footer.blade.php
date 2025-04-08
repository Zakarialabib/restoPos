<div class="flex-shrink-0 p-4 border-t border-orange-400">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <span class="material-icons text-orange-200">account_circle</span>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-white">
                {{ Auth::user()->name ?? '' }}
            </p>
            <p class="text-xs text-orange-200">
                {{ Auth::user()->email ?? '' }}
            </p>
        </div>
    </div>
</div>