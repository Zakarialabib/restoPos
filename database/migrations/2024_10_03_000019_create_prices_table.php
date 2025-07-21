<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuidMorphs('priceable');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('previous_price', 10, 2)->nullable();
            $table->decimal('previous_cost', 10, 2)->nullable();
            $table->string('size')->nullable();
            $table->timestamp('entry_date')->nullable();
            $table->timestamp('effective_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_current')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index('is_current');
            $table->index('effective_date');
            $table->index('expiry_date');
            $table->index(['priceable_type', 'priceable_id', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
