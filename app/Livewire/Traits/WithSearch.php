<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

trait WithSearch
{
    public string $search = '';
    public array $searchFields = [];
    protected int $searchDebounce = 300;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function applySearch($query)
    {
        if (empty($this->search) || empty($this->searchFields)) {
            return $query;
        }

        return $query->where(function ($query): void {
            foreach ($this->searchFields as $field) {
                $query->orWhere($field, 'like', '%' . $this->search . '%');
            }
        });
    }
}
