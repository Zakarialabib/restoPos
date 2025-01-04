<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

trait WithSorting
{
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function sortBy(string $field): void
    {
        $this->sortDirection = $this->sortField === $field
            ? 'asc' === $this->sortDirection ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    protected function applySorting($query)
    {
        return $query->orderBy($this->sortField, $this->sortDirection);
    }
}
