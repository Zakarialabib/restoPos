<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'stock',
        'is_available',
        'image',
        'is_composable',
        'volume', // Add volume attribute
        'instructions', // Add instructions attribute
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_composable' => 'boolean',
        'ingredients' => 'array',
    ];

    public function isLowStock()
    {
        return $this->stock <= $this->low_stock_threshold;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryAlerts()
    {
        return $this->hasMany(InventoryAlert::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('stock');
    }

    public function composables()
    {
        return $this->belongsToMany(Composable::class, 'composable_product');
    }

    // slug boot method
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model): void {
            $model->slug = Str::slug($model->name);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock', '<=', 10);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' DH';
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100; // Store price in cents
    }

    public function orders()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotalStock()
    {
        return $this->ingredients->sum('pivot.stock');
    }

    public function logProductCreation()
    {
        Log::info('Product created: ' . $this->name);
    }

    public static $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'stock' => 'required|integer|min:0',
        'is_available' => 'boolean',
    ];
}
