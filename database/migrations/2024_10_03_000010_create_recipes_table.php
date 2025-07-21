<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('instructions')->nullable();
            $table->json('preparation_steps')->nullable();
            $table->text('preparation_notes')->nullable();
            $table->integer('preparation_time')->nullable();
            $table->json('cooking_instructions')->nullable();
            $table->integer('cooking_time')->nullable();
            $table->integer('total_time')->nullable();
            $table->string('type')->default('standard');
            $table->boolean('is_featured')->default(false);
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->timestamp('last_cost_update')->nullable();
            $table->string('difficulty_level')->nullable();
            $table->integer('serving_size')->nullable();
            $table->json('equipment_needed')->nullable();
            $table->text('tips')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('popularity_score')->default(0);
            $table->text('chef_notes')->nullable();
            $table->json('allergens')->nullable();
            $table->integer('calories_per_serving')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

            // Indexes for better performance
            $table->index('type');
            $table->index('status');
            $table->index('is_featured');
            $table->index('popularity_score');
            $table->index('difficulty_level');
            $table->index('is_published');
            $table->index('published_at');
            $table->fullText(['name', 'description']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
