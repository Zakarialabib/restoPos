<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIngredientCreation(): void
    {
        $response = $this->post('/ingredients', [
            'name' => 'Test Ingredient',
            'quantity' => 10,
        ]);
        $response->assertStatus(201);
    }
}
