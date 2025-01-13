<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('sku')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('conversion_rate', 10, 2)->default(1);
            $table->decimal('stock_quantity', 10, 2)->default(0);
            $table->date('entry_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->decimal('reorder_point', 10, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_seasonal')->default(false);
            $table->string('image')->nullable();
            $table->boolean('is_composable')->default(false);
            $table->integer('popularity')->default(0);
            $table->decimal('portion_size', 10, 2)->nullable();
            $table->string('portion_unit')->nullable();
            $table->json('storage_conditions')->nullable();
            $table->json('supplier_info')->nullable();
            $table->decimal('minimum_order_quantity', 10, 2)->nullable();
            $table->integer('lead_time_days')->nullable();
            $table->json('allergens')->nullable();
            $table->string('type')->nullable();
            
            // Indexes for better performance
            $table->index('type');
            $table->index(['stock_quantity', 'reorder_point']);
            $table->index(['category_id', 'status']);
            $table->index('is_seasonal');
            $table->index('popularity');
            $table->index('expiry_date');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
