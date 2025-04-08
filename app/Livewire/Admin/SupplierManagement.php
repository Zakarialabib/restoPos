<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierManagement extends Component
{
    use WithPagination;

    public array $stats = [];
    public ?string $selectedSupplier = null;
    public ?string $name = '';
    public ?string $contact_person = '';
    public ?string $email = '';
    public ?string $phone = '';
    public ?string $address = '';
    public ?string $tax_number = '';
    public ?string $payment_terms = '';
    public ?string $notes = '';
    public bool $status = true;
    public bool $showSupplierModal = false;
    public bool $showDeleteModal = false;
    public ?string $search = '';

    protected array $rules = [
        'name' => 'required|string|max:255',
        'contact_person' => 'required|string|max:255',
        'email' => 'required|email|unique:suppliers,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'tax_number' => 'nullable|string|max:255',
        'payment_terms' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'status' => 'boolean',
    ];

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->stats = [
            'total_suppliers' => Supplier::count(),
            'active_suppliers' => Supplier::where('status', true)->count(),
            'total_purchase_orders' => Supplier::withCount('purchaseOrders')->get()->sum('purchase_orders_count'),
            'total_ingredients' => Supplier::withCount('ingredients')->get()->sum('ingredients_count'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.supplier-management', [
            'suppliers' => Supplier::withCount(['purchaseOrders', 'ingredients'])
                ->when($this->search, function ($query): void {
                    $query->where(function ($q): void {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }

    public function createSupplier(): void
    {
        $this->validate();

        Supplier::create([
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'tax_number' => $this->tax_number,
            'payment_terms' => $this->payment_terms,
            'notes' => $this->notes,
            'status' => $this->status,
        ]);

        $this->resetForm();
        $this->loadStats();
        $this->dispatch('supplier-created');
    }

    public function editSupplier(string $id): void
    {
        $supplier = Supplier::findOrFail($id);
        $this->selectedSupplier = $id;
        $this->name = $supplier->name;
        $this->contact_person = $supplier->contact_person;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
        $this->tax_number = $supplier->tax_number;
        $this->payment_terms = $supplier->payment_terms;
        $this->notes = $supplier->notes;
        $this->status = $supplier->status;
        $this->showSupplierModal = true;
    }

    public function updateSupplier(): void
    {
        $this->validate([
            'email' => 'required|email|unique:suppliers,email,' . $this->selectedSupplier,
        ]);

        $supplier = Supplier::findOrFail($this->selectedSupplier);
        $supplier->update([
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'tax_number' => $this->tax_number,
            'payment_terms' => $this->payment_terms,
            'notes' => $this->notes,
            'status' => $this->status,
        ]);

        $this->resetForm();
        $this->loadStats();
        $this->dispatch('supplier-updated');
    }

    public function deleteSupplier(string $id): void
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        $this->loadStats();
        $this->dispatch('supplier-deleted');
    }

    public function resetForm(): void
    {
        $this->reset([
            'selectedSupplier',
            'name',
            'contact_person',
            'email',
            'phone',
            'address',
            'tax_number',
            'payment_terms',
            'notes',
            'status',
            'showSupplierModal',
            'showDeleteModal',
        ]);
        $this->resetErrorBag();
    }

    public function getListeners(): array
    {
        return [
            'supplier-created' => 'loadStats',
            'supplier-updated' => 'loadStats',
            'supplier-deleted' => 'loadStats',
        ];
    }
}
