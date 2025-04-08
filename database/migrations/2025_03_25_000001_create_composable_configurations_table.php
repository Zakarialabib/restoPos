<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composable_configurations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('has_base')->default(false);
            $table->boolean('has_sugar')->default(false);
            $table->boolean('has_size')->default(false);
            $table->boolean('has_addons')->default(false);
            $table->integer('min_ingredients')->default(1);
            $table->integer('max_ingredients')->nullable();
            $table->json('sizes')->nullable();
            $table->json('base_types')->nullable();
            $table->json('sugar_types')->nullable();
            $table->json('addon_types')->nullable();
            $table->json('icons')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composable_configurations');
    }
};