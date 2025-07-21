<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('composable_configurations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->json('sizes')->nullable();
            $table->json('base_types')->nullable();
            $table->json('sugar_types')->nullable();
            $table->json('addon_types')->nullable();
            $table->json('customization_options')->nullable();
            $table->json('price_modifiers')->nullable();
            $table->json('icons')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['category_id', 'type']);
        });

        Schema::create('portion_configurations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->json('sizes')->nullable();
            $table->json('addons')->nullable();
            $table->json('sides')->nullable();
            $table->json('upgrades')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['category_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portion_configurations');
        Schema::dropIfExists('composable_configurations');
    }
};
