<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('product_id')->nullable()->constrained()->onDelete('cascade');// not working in case of multiple products in a single order
            $table->foreignUuid('order_id')->constrained()->onDelete('cascade'); // Foreign key to orders
            $table->integer('quantity');
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->json('details')->nullable(); // Store ingredients as JSON
            $table->timestamps();
            $table->index('order_id'); // Index on order_id for faster queries
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
