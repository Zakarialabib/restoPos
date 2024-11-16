<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table): void {
            $table->id();
            $table->morphs('priceable');
            $table->decimal('cost', 10, 2);
            $table->decimal('price', 10, 2);
            $table->datetime('date');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Index for better query performance
            $table->index(['priceable_type', 'priceable_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
