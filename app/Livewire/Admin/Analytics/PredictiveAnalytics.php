<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Analytics;

use App\Models\Product;
use App\Services\PredictionService;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class PredictiveAnalytics extends Component
{
    public $timeRange = 'week';
    public $predictions = [];
    protected PredictionService $predictionService;

    public function mount(PredictionService $predictionService): void
    {
        $this->predictionService = $predictionService;
        $this->loadPredictions();
    }

    public function loadPredictions(): void
    {
        $this->predictions = [
            'inventory_forecast' => $this->getPredictedInventory(),
            'peak_hours' => $this->getPeakHours(),
            'stock_recommendations' => $this->getStockRecommendations(),
            'seasonal_trends' => $this->getSeasonalTrends(),
        ];
    }

    public function applyRecommendations(): void
    {
        foreach ($this->predictions['inventory_forecast'] as $prediction) {
            if ('restock' === $prediction['action']) {
                $this->applyRecommendation($prediction['id']);
            }
        }
        session()->flash('success', __('All recommendations have been applied successfully.'));
    }

    public function applyRecommendation(int $productId): void
    {
        $prediction = collect($this->predictions['inventory_forecast'])
            ->firstWhere('id', $productId);

        if ( ! $prediction) {
            return;
        }

        $product = Product::find($productId);
        if ( ! $product) {
            return;
        }

        DB::beginTransaction();
        try {
            // Update stock level
            $difference = $prediction['recommended_stock'] - $prediction['current_stock'];
            if ($difference > 0) {
                $product->incrementStock($difference, 'Automatic restock based on AI recommendation');
            } elseif ($difference < 0) {
                $product->decrementStock(abs($difference), 'Stock optimization based on AI recommendation');
            }

            DB::commit();
            session()->flash('success', __('Stock level updated successfully for :product.', ['product' => $product->name]));
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Failed to update stock level: :message', ['message' => $e->getMessage()]));
        }
    }

    #[On('refreshPredictions')]
    public function refreshPredictions(): void
    {
        $this->loadPredictions();
        $this->dispatch('predictionsUpdated', $this->predictions);
    }

    public function render()
    {
        return view('livewire.admin.analytics.predictive-analytics');
    }

    protected function getPredictedInventory(): array
    {
        $products = Product::with(['stockLogs', 'orderItems'])->get();
        $predictions = [];

        foreach ($products as $product) {
            $stockPrediction = $this->predictionService->predictStockNeeds($product);
            $predictions[] = [
                'id' => $product->id,
                'name' => $product->name,
                'current_stock' => $product->stock_quantity,
                'recommended_stock' => $stockPrediction['recommended_stock'],
                'confidence' => $stockPrediction['confidence'],
                'action' => $this->determineStockAction(
                    $product->stock_quantity,
                    $stockPrediction['recommended_stock']
                ),
            ];
        }

        return $predictions;
    }


    protected function enrichSpecialEvents(array $events): array
    {
        return array_map(fn ($event) => [
            ...$event,
            'recommendations' => $this->generateEventRecommendations($event),
        ], $events);
    }

    protected function generateEventRecommendations(array $event): array
    {
        $recommendations = [];

        if ($event['impact'] > 0) {
            $recommendations[] = __('Increase stock levels by :percent%', ['percent' => ceil($event['impact'])]);
            $recommendations[] = __('Schedule additional staff');
        } else {
            $recommendations[] = __('Optimize stock to prevent excess');
            $recommendations[] = __('Consider promotions to boost sales');
        }

        if (abs($event['impact']) > 20) {
            $recommendations[] = __('Review historical data for similar events');
        }

        return $recommendations;
    }

    protected function determineStockAction(float $current, float $recommended): string
    {
        $difference = $recommended - $current;
        $threshold = $recommended * 0.1; // 10% threshold

        if ($difference > $threshold) {
            return 'restock';
        }
        if ($difference < -$threshold) {
            return 'reduce';
        }

        return 'maintain';
    }

    protected function calculateTrend(float $current, float $previous): float
    {
        if (0 === $previous) {
            return 0;
        }

        return (($current - $previous) / $previous) * 100;
    }
}
