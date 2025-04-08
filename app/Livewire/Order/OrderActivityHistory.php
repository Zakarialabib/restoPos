<?php

declare(strict_types=1);

namespace App\Livewire\Order;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class OrderActivityHistory extends Component
{
    use WithPagination;

    public Order $order;
    public ?string $selectedActivityId = null;
    public ?string $actionFilter = null;
    public ?string $dateFilter = null;
    public ?string $userFilter = null;

    #[Computed]
    public function activities()
    {
        return ActivityLog::query()
            ->where('subject_type', Order::class)
            ->where('subject_id', $this->order->id)
            ->when($this->actionFilter, function (Builder $query): void {
                $query->where('action', $this->actionFilter);
            })
            ->when($this->dateFilter, function (Builder $query): void {
                $query->whereDate('created_at', $this->dateFilter);
            })
            ->when($this->userFilter, function (Builder $query): void {
                $query->where('causer_id', $this->userFilter);
            })
            ->with('causer:id,name')
            ->latest()
            ->paginate(10);
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->whereHas('activityLogs', function (Builder $query): void {
                $query->where('subject_type', Order::class)
                    ->where('subject_id', $this->order->id);
            })
            ->select('id', 'name')
            ->get();
    }

    public function mount(Order $order): void
    {
        $this->order = $order;
    }

    public function toggleDetails(string $activityId): void
    {
        $this->selectedActivityId = $this->selectedActivityId === $activityId ? null : $activityId;
    }

    public function resetFilters(): void
    {
        $this->actionFilter = null;
        $this->dateFilter = null;
        $this->userFilter = null;
        $this->resetPage();
    }

    #[Layout('layouts.app')]
    #[Title('Order Activity History')]
    public function render()
    {
        return view('livewire.order.order-activity-history');
    }
}
