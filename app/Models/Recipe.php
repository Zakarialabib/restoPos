<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RecipeType;
use App\Enums\UnitType;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Recipe extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasUuids;
    use Prunable;
    use SoftDeletes;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'slug',
        'type',
        'status',
        'estimated_cost',
        'popularity_score',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'instructions',
        'preparation_steps',
        'preparation_notes',
        'preparation_time',
        'cooking_instructions',
        'equipment_needed',
        'difficulty_level',
        'type',
        'is_featured',
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
        'chef_notes',
        'allergens',
        'calories_per_serving',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'instructions' => 'array',
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
        'allergens' => 'array',
        'calories_per_serving' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'deleted_at' => 'datetime',
        'preparation_steps' => 'array',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    // New method to validate recipe completeness
    public function validateRecipe(): array
    {
        $errors = [];

        if (empty($this->ingredients()->count())) {
            $errors[] = 'Recipe must have at least one ingredient';
        }

        if (empty($this->preparation_steps) && empty($this->instructions)) {
            $errors[] = 'Recipe must have preparation steps or cooking instructions';
        }

        if (empty($this->preparation_time)) {
            $errors[] = 'Preparation time is required';
        }

        return $errors;
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subYears(2))
            ->whereDoesntHave('product');
    }

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

            return $quantity * $ingredient->cost;
        });

        // Only update and save if the model exists to prevent validation errors
        if ($this->exists) {
            $this->estimated_cost = $totalCost;
            $this->last_cost_update = now();
            $this->save();
        }

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

            return array_map(fn ($value, $ingredientValue) => ($value ?? 0) + ($ingredientValue ?? 0), $total, $nutrition);
        }, []);
    }

    // Performance Tracking Methods
    public function updatePopularityScore(): void
    {
        $thirtyDaysAgo = now()->subDays(30);

        $score = $this->product()
            ->withCount(['orderItems' => function ($query) use ($thirtyDaysAgo): void {
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
            ->with(['orderItems' => function ($query) use ($startDate, $endDate): void {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->first();

        if ( ! $metrics) {
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
        $totalRevenue = $metrics->orderItems->sum(fn ($item) => $item->quantity * $item->price);
        $totalCost = $metrics->orderItems->sum(fn ($item) => $item->quantity * $item->cost);
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
        return $query->whereHas('ingredients', function ($query) use ($ingredientIds): void {
            $query->whereIn('ingredients.id', (array) $ingredientIds);
        });
    }

    public function scopeNeedsCostUpdate(Builder $query, int $daysThreshold = 7): Builder
    {
        return $query->where(function ($query) use ($daysThreshold): void {
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

        if ( ! $pivotIngredient) {
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
        $this->ingredients->each(function ($ingredient) use ($clone): void {
            $clone->ingredients()->attach($ingredient->id, [
                'quantity' => $ingredient->pivot->quantity,
                'unit' => $ingredient->pivot->unit,
                'preparation_notes' => $ingredient->pivot->preparation_notes,
            ]);
        });

        return $clone;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($recipe): void {
            if ( ! $recipe->slug) {
                $recipe->slug = str($recipe->name)->slug();
            }
        });

        static::updating(function ($recipe): void {
            if ($recipe->isDirty('name') && ! $recipe->isDirty('slug')) {
                $recipe->slug = str($recipe->name)->slug();
            }
        });
    }

    // New computed property for complete recipe steps
    protected function formattedSteps(): Attribute
    {
        return Attribute::make(
            get: function () {
                $steps = [];

                // Add preparation steps
                if ( ! empty($this->preparation_steps)) {
                    foreach ($this->preparation_steps as $index => $step) {
                        $steps[] = [
                            'order' => $index + 1,
                            'type' => 'preparation',
                            'instruction' => $step,
                            'time' => null
                        ];
                    }
                }

                // Add cooking instructions
                if ( ! empty($this->instructions)) {
                    foreach ($this->instructions as $index => $instruction) {
                        $steps[] = [
                            'order' => count($steps) + $index + 1,
                            'type' => 'cooking',
                            'instruction' => $instruction,
                            'time' => null
                        ];
                    }
                }

                return collect($steps)->sortBy('order')->values()->all();
            }
        );
    }

    // Computed Properties
    protected function totalTime(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->preparation_time ?? 0) + ($this->cooking_time ?? 0)
        );
    }

    protected function isComplete(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ingredients()->exists() &&
                ! empty($this->instructions) &&
                $this->preparation_time > 0
        );
    }
}
