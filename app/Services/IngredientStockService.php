<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ingredient;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IngredientStockService
{
    public function adjustStock(Ingredient $ingredient, float $adjustment, string $reason): void
    {
        DB::transaction(function () use ($ingredient, $adjustment, $reason): void {
            $previousQuantity = $ingredient->stock_quantity;
            $newQuantity = $previousQuantity + $adjustment;

            $ingredient->stockLogs()->create([
                'adjustment' => $adjustment,
                'reason' => $reason,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $newQuantity,
                'user_id' => Auth::id(),
            ]);

            $ingredient->update(['stock_quantity' => $newQuantity]);
            $ingredient->updateStockStatus();
        });
    }

    public function updateStockStatus(Ingredient $ingredient): void
    {
        $wasRecentlyActive = $ingredient->status;

        // Update status based on stock level and expiry
        if ($ingredient->stock_quantity <= 0 ||
            ($ingredient->expiry_date && $ingredient->expiry_date <= now())) {
            $ingredient->status = false;
        } elseif ($ingredient->stock_quantity > 0 && ! $wasRecentlyActive &&
                  ( ! $ingredient->expiry_date || $ingredient->expiry_date > now())) {
            $ingredient->status = true;
        }

        if ($ingredient->isDirty('status')) {
            $ingredient->save();
        }
    }

    public function checkStockAvailability(Ingredient $ingredient, float $quantity): bool
    {
        if ($ingredient->expiry_date && $ingredient->expiry_date <= now()) {
            return false;
        }
        return $ingredient->stock_quantity >= $quantity;
    }

    public function getLowStockIngredients(?float $threshold = null): array
    {
        return Ingredient::query()
            ->where(function ($query) use ($threshold): void {
                $query->where('stock_quantity', '<=', $threshold ?? DB::raw('reorder_point'))
                    ->where('stock_quantity', '>', 0);
            })
            ->get()
            ->map(fn ($ingredient) => [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'current_stock' => $ingredient->stock_quantity,
                'reorder_point' => $ingredient->reorder_point,
                'unit' => $ingredient->unit,
                'category' => $ingredient->category->name,
            ])
            ->toArray();
    }

    public function getOutOfStockIngredients(): array
    {
        return Ingredient::query()
            ->where('stock_quantity', '<=', 0)
            ->get()
            ->map(fn ($ingredient) => [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'reorder_point' => $ingredient->reorder_point,
                'unit' => $ingredient->unit,
                'category' => $ingredient->category->name,
            ])
            ->toArray();
    }

    public function getStockAlerts(?string $categoryFilter = null): Collection
    {
        $query = Ingredient::query()
            ->when($categoryFilter, fn ($query) => $query->where('category_id', $categoryFilter))
            ->where('stock_quantity', '<=', DB::raw('reorder_point'))
            ->with('category');

        return $query->get()->map(fn ($ingredient) => [
            'id' => $ingredient->id,
            'name' => $ingredient->name,
            'category' => $ingredient->category->name,
            'stock_quantity' => $ingredient->stock_quantity,
            'reorder_point' => $ingredient->reorder_point,
            'unit' => $ingredient->unit,
            'status' => $ingredient->status,
        ]);
    }

    public function bulkAdjustStock(array $ingredients, float $quantity, string $reason): void
    {
        DB::transaction(function () use ($ingredients, $quantity, $reason): void {
            foreach ($ingredients as $ingredientId) {
                $ingredient = Ingredient::find($ingredientId);
                if ($ingredient) {
                    $this->adjustStock($ingredient, $quantity, $reason);
                }
            }
        });
    }

    public function updateReorderPoint(Ingredient $ingredient, float $reorderPoint): void
    {
        if ($reorderPoint < 0) {
            throw new Exception(__('Reorder point cannot be negative.'));
        }

        $ingredient->update(['reorder_point' => $reorderPoint]);
    }

    public function getStockMovements(Ingredient $ingredient, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        return $ingredient->stockLogs()
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate): void {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select([
                'adjustment',
                'reason',
                'previous_quantity',
                'new_quantity',
                'created_at'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTotalStockMovement(Ingredient $ingredient, ?Carbon $startDate = null, ?Carbon $endDate = null): float
    {
        return $ingredient->stockLogs()
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate): void {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum('adjustment');
    }
}
