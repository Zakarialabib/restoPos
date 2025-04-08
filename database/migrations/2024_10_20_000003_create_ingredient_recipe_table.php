<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ingredient_recipe', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('recipe_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('cost_at_time', 10, 2)->nullable();
            $table->decimal('waste_percentage', 5, 2)->default(0);
            $table->boolean('is_optional')->default(false);
            $table->integer('cooking_order')->default(0);
            $table->text('cooking_instructions')->nullable();
            $table->string('cooking_method')->nullable();
            $table->integer('cooking_time_minutes')->nullable();
            $table->integer('preparation_time_minutes')->nullable();
            $table->text('chef_notes')->nullable();
            $table->json('substitutes')->nullable();

            // Indexes for better performance
            $table->unique(['recipe_id', 'ingredient_id']);
            $table->index(['recipe_id', 'cooking_order']);
            $table->index('is_optional');
            $table->index(['cooking_method', 'cooking_time_minutes']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_recipe');
    }
};
