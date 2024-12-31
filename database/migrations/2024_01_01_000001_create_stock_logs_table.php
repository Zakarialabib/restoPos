<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table): void {
            $table->id();
            $table->morphs('stockable');
            $table->decimal('adjustment', 10, 2);
            $table->string('reason');
            $table->decimal('previous_quantity', 10, 2);
            $table->decimal('new_quantity', 10, 2);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
