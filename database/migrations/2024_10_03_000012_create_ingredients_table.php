<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->decimal('stock_quantity', 10, 2)->default(0);
            $table->decimal('reorder_point', 10, 2)->default(0);
            $table->string('unit');
            $table->string('portion_unit')->nullable();
            $table->decimal('portion_size', 10, 2)->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('base_cost', 10, 2)->nullable();
            $table->decimal('conversion_rate', 10, 2)->default(1);
            $table->date('entry_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('storage_location')->nullable();
            $table->json('storage_conditions')->nullable();
            $table->json('supplier_info')->nullable();
            $table->boolean('is_seasonal')->default(false);
            $table->string('season_start', 5)->nullable();
            $table->string('season_end', 5)->nullable();
            $table->decimal('markup_percentage', 5, 2)->nullable();
            $table->boolean('is_composable')->default(false);
            $table->integer('popularity')->default(0);
            $table->json('allergens')->nullable();
            $table->json('nutritional_info')->nullable();
            $table->json('preparation_instructions')->nullable();
            $table->decimal('minimum_order_quantity', 10, 2)->default(0);
            $table->integer('lead_time_days')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['category_id', 'type']);
            $table->index('status');
            $table->index('stock_quantity');
            $table->index(['stock_quantity', 'reorder_point']);
            $table->index(['category_id', 'status']);
            $table->index('is_seasonal');
            $table->index('popularity');
            $table->index('expiry_date');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
