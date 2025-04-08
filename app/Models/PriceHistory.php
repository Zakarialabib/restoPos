<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PriceHistory extends Model
{
    use HasAdvancedFilter;

    protected const ATTRIBUTES = [
        'id',
        'amount',
        'notes',
        'effective_date',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'amount',
        'notes',
        'effective_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'effective_date' => 'datetime',
    ];

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }
}
