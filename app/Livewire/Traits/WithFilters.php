<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

trait WithFilters
{
    public array $filters = [];
    public array $filterOptions = [];

    public function updatedFilters(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
        $this->resetPage();
    }

    protected function applyFilters($query)
    {
        foreach ($this->filters as $field => $value) {
            if (empty($value)) {
                continue;
            }

            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        return $query;
    }

    protected function getFilterOptions(string $field, $query = null): array
    {
        if (isset($this->filterOptions[$field])) {
            return $this->filterOptions[$field];
        }

        $query ??= $this->baseQuery();
        return $query->distinct()->pluck($field)->toArray();
    }
}
