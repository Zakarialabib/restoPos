<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ingredient;

class IngredientTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIngredientCreation()
    {
        $response = $this->post('/ingredients', [
            'name' => 'Test Ingredient',
            'quantity' => 10,
            'reorder_level' => 5,
        ]);
        $response->assertStatus(201);
    }
}