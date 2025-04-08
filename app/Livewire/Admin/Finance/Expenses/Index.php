<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Finance\Expenses;

use App\Models\Expense;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public array $stats = [];
    public ?string $selectedExpense = null;
    public ?string $category = '';
    public ?string $description = '';
    public ?float $amount = 0.0;
    public ?string $date = '';
    public ?string $payment_method = '';
    public ?string $reference_number = '';
    public ?string $notes = '';
    public ?array $attachments = [];
    public bool $showExpenseModal = false;
    public bool $showDeleteModal = false;
    public ?string $category_filter = '';
    public ?string $date_from = '';
    public ?string $date_to = '';
    public ?string $search = '';

    protected array $rules = [
        'category' => 'required|string|max:255',
        'description' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'date' => 'required|date',
        'payment_method' => 'required|string',
        'reference_number' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'attachments.*' => 'nullable|file|max:10240', // 10MB max
    ];

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->stats = [
            'total_expenses' => Expense::sum('amount'),
            'monthly_expenses' => Expense::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('amount'),
            'categories' => Expense::select('category')
                ->distinct()
                ->pluck('category')
                ->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.finance.expenses.index', [
            'expenses' => Expense::with('creator')
                ->when($this->category_filter, function ($query): void {
                    $query->where('category', $this->category_filter);
                })
                ->when($this->date_from, function ($query): void {
                    $query->whereDate('date', '>=', $this->date_from);
                })
                ->when($this->date_to, function ($query): void {
                    $query->whereDate('date', '<=', $this->date_to);
                })
                ->when($this->search, function ($query): void {
                    $query->where(function ($q): void {
                        $q->where('description', 'like', '%' . $this->search . '%')
                            ->orWhere('reference_number', 'like', '%' . $this->search . '%');
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }

    public function createExpense(): void
    {
        $this->validate();

        $expense = Expense::create([
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
            'notes' => $this->notes,
            'created_by' => auth()->id(),
        ]);

        if ($this->attachments) {
            $paths = [];
            foreach ($this->attachments as $attachment) {
                $paths[] = $attachment->store('expenses', 'public');
            }
            $expense->update(['attachments' => $paths]);
        }

        $this->resetForm();
        $this->loadStats();
        $this->dispatch('expense-created');
    }

    public function editExpense(string $id): void
    {
        $expense = Expense::findOrFail($id);
        $this->selectedExpense = $id;
        $this->category = $expense->category;
        $this->description = $expense->description;
        $this->amount = $expense->amount;
        $this->date = $expense->date->format('Y-m-d');
        $this->payment_method = $expense->payment_method;
        $this->reference_number = $expense->reference_number;
        $this->notes = $expense->notes;
        $this->showExpenseModal = true;
    }

    public function updateExpense(): void
    {
        $this->validate();

        $expense = Expense::findOrFail($this->selectedExpense);
        $expense->update([
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
            'notes' => $this->notes,
        ]);

        if ($this->attachments) {
            $paths = $expense->attachments ?? [];
            foreach ($this->attachments as $attachment) {
                $paths[] = $attachment->store('expenses', 'public');
            }
            $expense->update(['attachments' => $paths]);
        }

        $this->resetForm();
        $this->loadStats();
        $this->dispatch('expense-updated');
    }

    public function deleteExpense(string $id): void
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        $this->loadStats();
        $this->dispatch('expense-deleted');
    }

    public function resetForm(): void
    {
        $this->reset([
            'selectedExpense',
            'category',
            'description',
            'amount',
            'date',
            'payment_method',
            'reference_number',
            'notes',
            'attachments',
            'showExpenseModal',
            'showDeleteModal',
        ]);
        $this->resetErrorBag();
    }

    public function getListeners(): array
    {
        return [
            'expense-created' => 'loadStats',
            'expense-updated' => 'loadStats',
            'expense-deleted' => 'loadStats',
        ];
    }
}
