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
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Foreign key to orders
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->json('details'); // Store ingredients as JSON
            $table->timestamps();
            $table->index('order_id'); // Index on order_id for faster queries
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
