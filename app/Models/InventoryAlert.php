<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAlert extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'message', 'is_resolved'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
