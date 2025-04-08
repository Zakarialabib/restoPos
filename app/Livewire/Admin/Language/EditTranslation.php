<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Language;

use App\Models\Language;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditTranslation extends Component
{

    public $language;

    public $translations;

    public $rules = [
        'translations.*.value' => 'required',
    ];

    public function mount($id): void
    {
        $this->language = Language::where('code', $id)->firstOrFail();
        // dd($this->all());
        $this->translations = $this->getTranslations();
        $this->translations = collect($this->translations)->map(static fn ($item, $key): array => [
            'key'   => $key,
            'value' => $item,
        ])->toArray();
    }

    public function updateTranslation(): void
    {
        $this->validate();

        $path = base_path(sprintf('lang/%s.json', $this->language->code));

        $data = file_get_contents($path);
        $translations = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        foreach ($this->translations as $translation) {
            $translations[$translation['key']] = $translation['value'];
        }

        file_put_contents($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->alert('success', __('Data created successfully!'));
    }

    public function render()
    {
        return view('livewire.admin.language.edit-translation');
    }

    private function getTranslations()
    {
        $path = base_path(sprintf('lang/%s.json', $this->language->code));
        $content = file_get_contents($path);

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}
