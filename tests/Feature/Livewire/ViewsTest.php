<?php

declare(strict_types=1);

use App\Livewire\MenuIndex;
use App\Livewire\TvMenu;
use App\Livewire\Pos;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('Livewire Views', function () {
    // Test component presence on pages
    it('menu index page can be viewed and contains livewire component', function () {
        get('/')
            ->assertStatus(200)
            ->assertSeeLivewire(MenuIndex::class);
    });

    it('tv menu page can be viewed and contains livewire component', function () {
        get('/menu/tv')
            ->assertStatus(200)
            ->assertSeeLivewire(TvMenu::class);
    });

    it('pos page can be viewed and contains livewire component', function () {
        get('/pos')
            ->assertStatus(200)
            ->assertSeeLivewire(Pos::class);
    });

    // Test component rendering
    it('menu index component renders successfully', function () {
        livewire(MenuIndex::class)
            ->assertStatus(200)
            ->assertViewHas('categories')
            ->assertViewHas('products');
    });

    it('tv menu component renders successfully', function () {
        livewire(TvMenu::class)
            ->assertStatus(200)
            ->assertViewHas('categories');
    });

    it('pos component renders successfully', function () {
        livewire(Pos::class)
            ->assertStatus(200)
            ->assertViewHas('categories');
    });
}); 