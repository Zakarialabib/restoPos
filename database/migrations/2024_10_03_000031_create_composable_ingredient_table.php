<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('composable_ingredient', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('composable_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 8, 2);
            $table->string('unit');
            $table->timestamps();

            $table->unique(['composable_id', 'ingredient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composable_ingredient');
    }
};
