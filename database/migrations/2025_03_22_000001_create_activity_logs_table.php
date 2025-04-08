<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('causer_type')->nullable(); // The model that caused the activity (e.g., User)
            $table->uuid('causer_id')->nullable(); // The ID of the causer
            $table->string('subject_type'); // The model being tracked (e.g., Order, Ingredient)
            $table->uuid('subject_id'); // The ID of the subject
            $table->string('action'); // The action performed (e.g., created, updated, deleted)
            $table->string('description'); // Human-readable description
            $table->json('properties')->nullable(); // Additional data about the change
            $table->json('old_values')->nullable(); // Previous values
            $table->json('new_values')->nullable(); // New values
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
