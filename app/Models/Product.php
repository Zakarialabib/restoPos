<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'price',
        'cost',
        'category_id',
        'image',
        'is_available',
        'is_featured',
        'recipe_id',
        'is_composable',
        'stock',
        'reorder_point',
        'average_sales',
        'nutritional_info',
        'preparation_time',
        'storage_conditions',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock' => 'decimal:2',
        'reorder_point' => 'integer',
        'average_sales' => 'decimal:2',
        'nutritional_info' => 'array',
        'storage_conditions' => 'array',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryAlerts(): HasMany
    {
        return $this->hasMany(InventoryAlert::class);
    }

    public function composables(): BelongsToMany
    {
        return $this->belongsToMany(Composable::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    // Stock Management Methods
    public function updateStock(float $quantity, string $reason = 'Manual Update'): void
    {
        $oldStock = $this->stock;
        $this->stock += $quantity;
        $this->save();

        // Log stock changes
        StockLog::create([
            'product_id' => $this->id,
            'old_stock' => $oldStock,
            'new_stock' => $this->stock,
            'change' => $quantity,
            'reason' => $reason
        ]);

        // Update availability status
        $this->updateAvailabilityStatus();
    }

    // Scopes
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereRaw('stock <= reorder_point');
    }

    public function scopeInCategory(Builder $query, int|array $categoryIds): Builder
    {
        return $query->whereIn('category_id', (array) $categoryIds);
    }

    // Attributes
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn(int $value) => Number::format($value, locale: 'fr_MA'),
        );
    }

    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->stock <= 0) {
                    return 'Out of Stock';
                }
                return 'In Stock';
            }
        );
    }

    public function updateAvailabilityStatus(): void
    {
        $isAvailable = $this->stock > 0 && $this->hasRequiredIngredients();

        if ($this->is_available !== $isAvailable) {
            $this->is_available = $isAvailable;
            $this->save();
        }
    }

    public function hasRequiredIngredients(): bool
    {
        foreach ($this->ingredients as $ingredient) {
            if (!$ingredient->hasEnoughStock($ingredient->pivot->quantity)) {
                return false;
            }
        }
        return true;
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->reorder_point;
    }

    public function calculateCost(): float
    {
        return $this->ingredients->sum(function ($ingredient) {
            return $ingredient->cost * $ingredient->pivot->quantity;
        });
    }

    // Method to update product status
    public function updateStatus()
    {
        $this->is_available = $this->stock > 0;
        $this->save();
    }

    // Add method to calculate ingredient requirements
    public function calculateIngredientRequirements(int $quantity = 1): array
    {
        $requirements = [];
        
        foreach ($this->ingredients as $ingredient) {
            $requiredQuantity = $ingredient->pivot->quantity * $quantity;
            $requirements[$ingredient->id] = [
                'required' => $requiredQuantity,
                'available' => $ingredient->stock,
                'sufficient' => $ingredient->stock >= $requiredQuantity,
                'unit' => $ingredient->unit
            ];
        }
        
        return $requirements;
    }

    public function hasEnoughIngredients(int $quantity = 1): bool
    {
        $requirements = $this->calculateIngredientRequirements($quantity);
        return collect($requirements)->every(fn($req) => $req['sufficient']);
    }

    public function consumeIngredients(int $quantity = 1): void
    {
        if (!$this->hasEnoughIngredients($quantity)) {
            throw new \Exception('Insufficient ingredients for product: ' . $this->name);
        }

        DB::transaction(function () use ($quantity) {
            foreach ($this->ingredients as $ingredient) {
                $requiredQuantity = $ingredient->pivot->quantity * $quantity;
                $ingredient->decrementStock($requiredQuantity);
            }
        });
    }

    // Method to check if product can be made with current ingredient stocks
    public function isProductAvailable(int $quantity = 1): bool
    {
        foreach ($this->ingredients as $ingredient) {
            $pivotQuantity = $ingredient->pivot->stock;
            $totalRequired = $pivotQuantity * $quantity;

            if ($ingredient->stock < $totalRequired) {
                return false;
            }
        }
        return true;
    }

    // Nutritional Information Methods
    public function calculateNutritionalInfo(): array
    {
        $totalNutrition = [
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fat' => 0,
        ];

        foreach ($this->ingredients as $ingredient) {
            $quantity = $ingredient->pivot->quantity;
            $nutrition = $ingredient->calculateNutritionalInfo($quantity);

            foreach ($totalNutrition as $key => &$value) {
                $value += $nutrition[$key];
            }
        }

        return $totalNutrition;
    }

    protected function profit(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->price - $this->calculateCost(),
        );
    }

    protected function profitMargin(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->calculateCost() > 0
                ? (($this->price - $this->calculateCost()) / $this->price) * 100
                : 0,
        );
    }
}
