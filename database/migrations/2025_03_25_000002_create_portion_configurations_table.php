<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portion_configurations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // regular, combo, family, etc.
            $table->json('sizes')->nullable(); // [small, medium, large]
            $table->json('addons')->nullable(); // [extra cheese, extra sauce, etc.]
            $table->json('sides')->nullable(); // [fries, drink, salad, etc.]
            $table->json('upgrades')->nullable(); // [premium sides, larger drinks, etc.]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portion_configurations');
    }
}; 