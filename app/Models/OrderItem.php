<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class OrderItem extends Model
{
    // use SoftDeletes;

    use HasAdvancedFilter;

    protected const ATTRIBUTES = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'price',
        'cost',
        'notes',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        // 'cost',
        'notes',
        'total_amount',
        'details',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    // Static Analysis Methods
    public static function getTopPerformers(Carbon $startDate, Carbon $endDate, int $limit = 10): Collection
    {
        return static::with('product')
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(quantity * price) as total_revenue')
            ->selectRaw('SUM(quantity * (price - cost)) as total_profit')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderByDesc('total_profit')
            ->take($limit)
            ->get();
    }

    public static function getDailyRevenue(Carbon $startDate, Carbon $endDate): Collection
    {
        return static::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('SUM(quantity * price) as revenue')
            ->selectRaw('SUM(quantity * (price - cost)) as profit')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Price History Methods
    // protected static function booted(): void
    // {
    //     static::creating(function ($orderItem): void {
    //         if (!$orderItem->price) {
    //             $orderItem->price = $orderItem->product->price;
    //         }
    //         if (!$orderItem->cost) {
    //             $orderItem->cost = $orderItem->product->cost;
    //         }
    //     });

    //     static::created(function ($orderItem): void {
    //         PriceHistory::create([
    //             'product_id' => $orderItem->product_id,
    //             'old_price' => $orderItem->product->price,
    //             'new_price' => $orderItem->price,
    //             'reason' => 'Order item creation',
    //             'order_item_id' => $orderItem->id,
    //         ]);
    //     });
    // }

    // Performance Metrics
    public function getSubtotal(): float
    {
        return $this->quantity * $this->price;
    }

    public function getProfit(): float
    {
        return $this->quantity * ($this->price - $this->cost);
    }

    public function getProfitMargin(): float
    {
        $subtotal = $this->getSubtotal();
        return $subtotal > 0 ? ($this->getProfit() / $subtotal) * 100 : 0;
    }

    // Scopes
    public function scopeInDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeProfitable($query, float $minProfitMargin = 0)
    {
        return $query->whereRaw('(price - cost) / price * 100 >= ?', [$minProfitMargin]);
    }
}
