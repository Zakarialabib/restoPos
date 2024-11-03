<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->integer('unit'); // default to grams
            $table->decimal('cost', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->default(10);
            $table->decimal('conversion_rate', 8, 2)->default(1.00);
            $table->integer('stock')->default(0);
            $table->date('expiry_date')->nullable();
            $table->json('supplier_info')->nullable();
            $table->json('nutritional_info')->nullable();
            $table->json('storage_conditions')->nullable();
            $table->json('instructions')->nullable();
            $table->boolean('is_composable')->default(true);
            $table->foreignId('category_id')->constrained('categories')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
