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
            $table->date('date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_current')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
