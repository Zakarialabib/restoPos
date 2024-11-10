<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'old_stock',
        'new_stock',
        'change',
        'reason',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
