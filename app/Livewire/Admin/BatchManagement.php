<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Ingredient;
use Livewire\Component;

class BatchManagement extends Component
{
    public $batchNumber;
    public $expiryDate;
    public $ingredientId;

    public function addBatch(): void
    {
        $ingredient = Ingredient::find($this->ingredientId);
        $ingredient->batch_number = $this->batchNumber;
        $ingredient->expiry_date = $this->expiryDate;
        $ingredient->save();

        $this->reset(['batchNumber', 'expiryDate', 'ingredientId']);
        $this->dispatch('batchAdded');
    }


    public function render()
    {
        return view('livewire.admin.batch-management');
    }
}
