<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSlug;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'base_price',
        'category_id',
        'image',
        'is_available',
        'is_featured',
        'recipe_id',
        'is_composable',
        'nutritional_info',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'base_price' => 'decimal:2',
        'nutritional_info' => 'array',
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

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
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

    public function scopeInCategory(Builder $query, int|array $categoryIds): Builder
    {
        return $query->whereIn('category_id', (array) $categoryIds);
    }

    public function updateAvailabilityStatus(): void
    {
        $isAvailable = $this->hasRequiredIngredients();

        if ($this->is_available !== $isAvailable) {
            $this->is_available = $isAvailable;
            $this->save();
        }
    }

    public function hasRequiredIngredients(): bool
    {
        foreach ($this->ingredients as $ingredient) {
            if ( ! $ingredient->hasEnoughStock($ingredient->pivot->quantity)) {
                return false;
            }
        }
        return true;
    }

    public function calculateCost(): float
    {
        return $this->ingredients->sum(fn ($ingredient) => $ingredient->cost * $ingredient->pivot->quantity);
    }

    // Add method to calculate ingredient requirements
    public function calculateIngredientRequirements($quantity): array
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

    public function hasEnoughIngredients($quantity): bool
    {
        $requirements = $this->calculateIngredientRequirements($quantity);
        return collect($requirements)->every(fn ($req) => $req['sufficient']);
    }

    public function consumeIngredients($quantity): void
    {
        if ( ! $this->hasEnoughIngredients($quantity)) {
            throw new Exception('Insufficient ingredients for product: ' . $this->name);
        }

        DB::transaction(function () use ($quantity): void {
            foreach ($this->ingredients as $ingredient) {
                $requiredQuantity = $ingredient->pivot->quantity * $quantity;
                $ingredient->decrementStock($requiredQuantity);
            }
        });
    }

    // Method to check if product can be made with current ingredient stocks
    public function isProductAvailable($quantity): bool
    {
        foreach ($this->ingredients as $ingredient) {
            $pivotQuantity = $ingredient->pivot->quantity;
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

    // Add size-specific price methods
    public function getSizePrice(string $size): ?Price
    {
        return $this->prices()
            ->where('metadata->size', $size)
            ->where('date', '<=', now())
            ->latest('date')
            ->first();
    }

    public function addSizePrice(string $size, $cost, $price, $date = null, $notes = null): Price
    {
        return $this->prices()->create([
            'cost' => $cost,
            'price' => $price,
            'date' => $date ?? now(),
            'notes' => $notes,
            'metadata' => ['size' => $size],
        ]);
    }

    // Add method to get all available sizes with prices
    public function getAvailableSizes(): Collection
    {
        return $this->prices()
            ->where('date', '<=', now())
            ->get()
            ->groupBy('metadata.size')
            ->map(fn ($prices) => $prices->sortByDesc('date')->first());
    }

    // Attributes
    protected function price(): Attribute
    {
        return Attribute::make(
            get: function (int $value) {
                // Default to base price if no specific size price is set
                return Number::format($value, locale: 'fr_MA');
            }
        );
    }

    public function calculatePrice(): float
    {
        return $this->getAvailableSizes()->sum('price');
    }

    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ( ! $this->is_available) {
                    return 'Out of Stock';
                }
                return 'In Stock';
            }
        );
    }

    protected function profit(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price - $this->calculateCost(),
        );
    }

    protected function profitMargin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->calculateCost() > 0
                ? (($this->price - $this->calculateCost()) / $this->price) * 100
                : 0,
        );
    }
}
