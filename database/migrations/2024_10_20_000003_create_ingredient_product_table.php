<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ingredient_product', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('cost_at_time', 10, 2)->nullable();
            $table->decimal('waste_percentage', 5, 2)->default(0);
            $table->boolean('is_optional')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->integer('preparation_order')->default(0);
            $table->text('preparation_notes')->nullable();
            $table->json('substitutes')->nullable();

            // Indexes for better performance
            $table->unique(['product_id', 'ingredient_id']);
            $table->index(['product_id', 'preparation_order']);
            $table->index('is_optional');
            $table->index('is_visible');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_product');
    }
};
