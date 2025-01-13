<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function __construct(
        private readonly MenuService $menuService
    ) {}

    public function tvMenu()
    {
        return Inertia::render('Menu/TVMenu', [
            'products' => $this->menuService->getActiveProducts(),
        ]);
    }

    public function index()
    {
        return Inertia::render('Menu/Index', [
            'products' => ProductResource::collection($this->menuService->getActiveProducts()),
            'categories' => $this->menuService->getComposableCategories(),
        ]);
    }
}
