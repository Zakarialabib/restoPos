<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUuid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->json('options')->nullable();
            $table->text('notes')->nullable();
            $table->json('details')->nullable();
            $table->boolean('is_composable')->default(false);
            $table->json('composition')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['order_id', 'product_id']);
            $table->index('is_composable');
            $table->index('product_id');
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
