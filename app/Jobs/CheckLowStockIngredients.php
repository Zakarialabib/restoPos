<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Ingredient;
use App\Notifications\LowStockAlert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class CheckLowStockIngredients implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lowStockIngredients = Ingredient::whereColumn('stock', '<=', 'reorder_level')->get();
        foreach ($lowStockIngredients as $ingredient) {
            Notification::send($ingredient->users, new LowStockAlert($ingredient));
        }

        $nearExpiryIngredients = Ingredient::where('expiry_date', '<=', now()->addDays(7))->get();
        // Notify users about near expiry ingredients
    }
}
