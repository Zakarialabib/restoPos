<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Page;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class Template extends Component
{

    use WithFileUploads;

    public $templates = [];

    public $selectedTemplate = [];

    public $createTemplate;

    public $pages = [];

    public $selectTemplate;

    public $listeners = [
        'createTemplate',
    ];

    public function mount(): void
    {
        $this->templates = config('templates');
    }

    public function createTemplate(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createTemplate = true;
    }

    public function updatedSelectTemplate(): void
    {
        $this->selectedTemplate = $this->templates[$this->selectTemplate];
    }

    public function store(): void
    {
        try {
            $page = [
                'title'            => $this->selectedTemplate['title'],
                'slug'             => $this->selectedTemplate['slug'],
                'details'          => $this->selectedTemplate['details'],
                'meta_title'       => $this->selectedTemplate['meta_title'],
                'meta_description' => $this->selectedTemplate['meta_description'],
                'image'            => $this->selectedTemplate['image'],
            ];

            Page::create($page);

            $this->pages[] = $page;

            $this->dispatch('refreshIndex');

            $this->createTemplate = false;

            $this->alert('success', __('Page created successfully!'));
        } catch (Throwable) {
            $this->alert('warning', __('Page Was not created!'));
        }
    }

    public function render()
    {
        return view('livewire.admin.page.template');
    }
}
