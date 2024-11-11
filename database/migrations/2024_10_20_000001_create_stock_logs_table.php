<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockLogsTable extends Migration
{
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('old_stock', 10, 2);
            $table->decimal('new_stock', 10, 2);
            $table->decimal('change', 10, 2);
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
}
