<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Ingredient;
use App\Notifications\LowStockAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class CheckLowStockIngredients implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        $lowStockIngredients = Ingredient::where('stock', '<=', 10)->get();

        foreach ($lowStockIngredients as $ingredient) {
            Notification::send($ingredient->users, new LowStockAlert($ingredient));
        }
    }
}
