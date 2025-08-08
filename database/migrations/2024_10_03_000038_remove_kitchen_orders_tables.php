<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // Drop kitchen_order_items table first (due to foreign key constraint)
        Schema::dropIfExists('kitchen_order_items');
        
        // Drop kitchen_orders table
        Schema::dropIfExists('kitchen_orders');
    }

    public function down(): void
    {
        // Recreate kitchen_orders table
        Schema::create('kitchen_orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('order_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->foreignUuid('assigned_to')->nullable()->constrained('users');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('estimated_preparation_time')->comment('in minutes');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Recreate kitchen_order_items table
        Schema::create('kitchen_order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('kitchen_order_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('order_item_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }
}; 