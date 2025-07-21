<?php

declare(strict_types=1);

use App\Enums\ScheduleStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('section_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->json('recurrence_pattern')->nullable()->comment('JSON containing recurrence rules (daily, weekly, monthly, etc.)');
            $table->integer('display_duration')->default(0)->comment('Duration in seconds to display this section');
            $table->integer('priority')->default(0)->comment('Higher priority sections are displayed first');
            $table->string('status')->default(ScheduleStatus::DRAFT->value);
            $table->json('display_conditions')->nullable()->comment('JSON containing conditions for when to display this section');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['section_id', 'status']);
            $table->index(['start_time', 'end_time']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('section_schedules');
    }
};
