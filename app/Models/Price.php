<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Support\HasAdvancedFilter;

class Price extends Model
{
    use HasAdvancedFilter;

    protected const ATTRIBUTES = [
        'id',
        'amount',
        'price',
        'cost',
        'previous_cost',
        'previous_price',
        'effective_date',
        'is_current',
        'reason',
        'metadata',
        'priceable_type',
        'priceable_id'
    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'amount',
        'price',
        'cost',
        'previous_cost',
        'previous_price',
        'effective_date',
        'is_current',
        'reason',
        'metadata',
        'priceable_type',
        'priceable_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'previous_cost' => 'decimal:2',
        'previous_price' => 'decimal:2',
        'is_current' => 'boolean',
        'effective_date' => 'datetime',
        'metadata' => 'array'
    ];

    // Relationships
    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }

    // Computed Properties
    protected function priceChange(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                if (!$this->previous_price) {
                    return 0;
                }
                return $this->price - $this->previous_price;
            }
        );
    }

    protected function costChange(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                if (!$this->previous_cost) {
                    return 0;
                }
                return $this->cost - $this->previous_cost;
            }
        );
    }

    protected function priceChangePercentage(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                if (!$this->previous_price || $this->previous_price <= 0) {
                    return 0;
                }
                return (($this->price - $this->previous_price) / $this->previous_price) * 100;
            }
        );
    }

    protected function costChangePercentage(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                if (!$this->previous_cost || $this->previous_cost <= 0) {
                    return 0;
                }
                return (($this->cost - $this->previous_cost) / $this->previous_cost) * 100;
            }
        );
    }

    protected function profitMargin(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                if (!$this->price || $this->price <= 0) {
                    return 0;
                }
                return (($this->price - $this->cost) / $this->price) * 100;
            }
        );
    }

    // Scopes
    public function scopeCurrentPrices($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('effective_date', '<=', $date)
            ->orderByDesc('effective_date')
            ->limit(1);
    }

    public function scopeWithinPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('effective_date', [$startDate, $endDate]);
    }
}
