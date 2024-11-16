<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('product_customizations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Will contain both languages e.g. "Size - الحجم"
            $table->string('type'); // base, topping, sauce, etc.
            $table->decimal('price', 10, 2);
            $table->boolean('is_required')->default(false);
            $table->integer('max_selections')->default(1);
            $table->json('options'); // Available options for this customization
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_customizations');
    }
}; 