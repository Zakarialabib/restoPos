<div>
    <div>
        <h2>Batch Management</h2>
        <form wire:submit="addBatch">
            <select wire:model="ingredientId">
                <option value="">Select Ingredient</option>
                @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
            <input type="text" wire:model="batchNumber" placeholder="Batch Number" required>
            <input type="date" wire:model="expiryDate" required>
            <button type="submit">Add Batch</button>
        </form>
    </div>
</div>
