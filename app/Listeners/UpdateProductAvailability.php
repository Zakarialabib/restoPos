<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ProductStockUpdated;

class UpdateProductAvailability
{
    public function handle(ProductStockUpdated $event): void
    {
        $product = $event->product;
        $product->is_available = $product->stock > 0;
        $product->save();
    }
}
