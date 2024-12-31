<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            // $table->string('sku')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('conversion_rate', 10, 2)->default(1);
            $table->decimal('stock_quantity', 10, 2)->default(0);
            $table->date('entry_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->decimal('reorder_point', 10, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_seasonal')->default(false);
            $table->string('image')->nullable();
            $table->json('nutritional_info')->nullable();
            $table->boolean('is_composable')->default(false);
            $table->index(['stock_quantity', 'reorder_point']);
            $table->index(['category_id', 'status']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
