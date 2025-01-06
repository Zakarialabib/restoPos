<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RecipeType;
use App\Enums\UnitType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Recipe extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'instructions',
        'preparation_time',
        'type',
        'is_featured',
        'nutritional_info',
        'estimated_cost',
        'last_cost_update',
        'difficulty_level',
        'serving_size',
        'cooking_time',
        'total_time',
        'equipment_needed',
        'tips',
        'video_url',
        'status',
        'popularity_score',
    ];

    protected $casts = [
        'instructions' => 'array',
        'nutritional_info' => 'array',
        'is_featured' => 'boolean',
        'estimated_cost' => 'decimal:2',
        'last_cost_update' => 'datetime',
        'preparation_time' => 'integer',
        'cooking_time' => 'integer',
        'total_time' => 'integer',
        'equipment_needed' => 'array',
        'type' => RecipeType::class,
        'status' => 'boolean',
        'popularity_score' => 'integer',
    ];

    // Relationships
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipe')
            ->withPivot(['quantity', 'unit', 'preparation_notes'])
            ->withTimestamps();
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }

    // Cost Management Methods
    public function calculateTotalCost(): float
    {
        $totalCost = $this->ingredients->sum(function ($ingredient) {
            $quantity = $ingredient->pivot->quantity;
            $unit = UnitType::from($ingredient->pivot->unit);
            
            // Convert quantity to ingredient's base unit if necessary
            if ($unit !== $ingredient->unit) {
                $quantity = $unit->convertTo($ingredient->unit, $quantity);
            }
            
            return $quantity * $ingredient->cost_per_unit;
        });

        $this->estimated_cost = $totalCost;
        $this->last_cost_update = now();
        $this->save();

        return $totalCost;
    }

    public function updateCostAndNotify(): void
    {
        $oldCost = $this->estimated_cost;
        $newCost = $this->calculateTotalCost();

        if ($oldCost !== $newCost) {
            Log::info("Recipe cost updated: {$this->name}", [
                'old_cost' => $oldCost,
                'new_cost' => $newCost,
                'difference' => $newCost - $oldCost
            ]);

            // Update associated product cost if exists
            if ($this->product) {
                $this->product->updateCost($newCost, 'Recipe cost update');
            }
        }
    }

    // Ingredient Management Methods
    public function updateIngredient(Ingredient $ingredient, float $quantity, string $unit, ?string $preparationNotes = null): void
    {
        $this->ingredients()->syncWithoutDetaching([
            $ingredient->id => [
                'quantity' => $quantity,
                'unit' => $unit,
                'preparation_notes' => $preparationNotes
            ]
        ]);

        $this->updateCostAndNotify();
    }

    public function removeIngredient(Ingredient $ingredient): void
    {
        $this->ingredients()->detach($ingredient->id);
        $this->updateCostAndNotify();
    }

    public function hasRequiredIngredients(): bool
    {
        return $this->ingredients->every(function ($ingredient) {
            $quantity = $ingredient->pivot->quantity;
            $unit = UnitType::from($ingredient->pivot->unit);
            
            if ($unit !== $ingredient->unit) {
                $quantity = $unit->convertTo($ingredient->unit, $quantity);
            }
            
            return $ingredient->stock_quantity >= $quantity;
        });
    }

    // Nutritional Calculations
    public function calculateNutritionalInfo(): array
    {
        return $this->ingredients->reduce(function (array $total, Ingredient $ingredient) {
            $quantity = $ingredient->pivot->quantity;
            $unit = UnitType::from($ingredient->pivot->unit);
            
            if ($unit !== $ingredient->unit) {
                $quantity = $unit->convertTo($ingredient->unit, $quantity);
            }

            $nutrition = $ingredient->calculateNutrition($quantity);

            return array_map(function ($value, $ingredientValue) {
                return ($value ?? 0) + ($ingredientValue ?? 0);
            }, $total, $nutrition);
        }, []);
    }

    public function updateNutritionalInfo(): void
    {
        $this->nutritional_info = $this->calculateNutritionalInfo();
        $this->save();
    }

    // Performance Tracking Methods
    public function updatePopularityScore(): void
    {
        $thirtyDaysAgo = now()->subDays(30);
        
        $score = $this->product()
            ->withCount(['orderItems' => function ($query) use ($thirtyDaysAgo) {
                $query->where('created_at', '>=', $thirtyDaysAgo);
            }])
            ->first()
            ->order_items_count ?? 0;

        $this->popularity_score = $score;
        $this->save();
    }

    public function getPerformanceMetrics(Carbon $startDate, Carbon $endDate): array
    {
        $metrics = $this->product()
            ->with(['orderItems' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->first();

        if (!$metrics) {
            return [
                'total_orders' => 0,
                'total_quantity' => 0,
                'total_revenue' => 0,
                'total_cost' => 0,
                'profit' => 0,
                'profit_margin' => 0,
            ];
        }

        $totalQuantity = $metrics->orderItems->sum('quantity');
        $totalRevenue = $metrics->orderItems->sum(fn($item) => $item->quantity * $item->price);
        $totalCost = $metrics->orderItems->sum(fn($item) => $item->quantity * $item->cost);
        $profit = $totalRevenue - $totalCost;

        return [
            'total_orders' => $metrics->orderItems->count(),
            'total_quantity' => $totalQuantity,
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'profit' => $profit,
            'profit_margin' => $totalRevenue > 0 ? ($profit / $totalRevenue) * 100 : 0,
        ];
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('popularity_score');
    }

    public function scopeByType(Builder $query, RecipeType $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeWithIngredient(Builder $query, int|array $ingredientIds): Builder
    {
        return $query->whereHas('ingredients', function ($query) use ($ingredientIds) {
            $query->whereIn('ingredients.id', (array) $ingredientIds);
        });
    }

    public function scopeNeedsCostUpdate(Builder $query, int $daysThreshold = 7): Builder
    {
        return $query->where(function ($query) use ($daysThreshold) {
            $query->whereNull('last_cost_update')
                ->orWhere('last_cost_update', '<=', now()->subDays($daysThreshold));
        });
    }

    // Helper Methods
    public function getTotalTime(): int
    {
        return $this->preparation_time + ($this->cooking_time ?? 0);
    }

    public function canBePrepared(): bool
    {
        return $this->hasRequiredIngredients() && $this->status;
    }

    public function getIngredientQuantity(Ingredient $ingredient, ?UnitType $targetUnit = null): ?float
    {
        $pivotIngredient = $this->ingredients->find($ingredient->id);
        
        if (!$pivotIngredient) {
            return null;
        }

        $quantity = $pivotIngredient->pivot->quantity;
        $sourceUnit = UnitType::from($pivotIngredient->pivot->unit);
        
        if ($targetUnit && $sourceUnit !== $targetUnit) {
            return $sourceUnit->convertTo($targetUnit, $quantity);
        }

        return $quantity;
    }

    public function duplicate(): self
    {
        $clone = $this->replicate(['popularity_score']);
        $clone->name = "{$this->name} (Copy)";
        $clone->save();

        // Clone ingredient relationships
        $this->ingredients->each(function ($ingredient) use ($clone) {
            $clone->ingredients()->attach($ingredient->id, [
                'quantity' => $ingredient->pivot->quantity,
                'unit' => $ingredient->pivot->unit,
                'preparation_notes' => $ingredient->pivot->preparation_notes,
            ]);
        });

        return $clone;
    }
}
