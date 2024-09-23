<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ProductStockUpdated;
use App\Models\Product;

class UpdateProductAvailability
{
    public function handle(ProductStockUpdated $event)
    {
        $product = $event->product;
        $product->is_available = $product->stock > 0;
        $product->save();
    }
}