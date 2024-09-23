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
            $table->string('type')->nullable(); // fruit, liquid, ice
            $table->string('unit')->default('g'); // default to grams
            $table->float('conversion_rate')->default(1); // 1g = 1ml juice for simplicity
            $table->decimal('stock', 8, 2);
            $table->string('batch_number');
            $table->date('expiry_date');
            $table->string('volume')->nullable();
            $table->decimal('reorder_level', 8, 2);
            // $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
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
