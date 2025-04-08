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
            $table->uuidMorphs('priceable');
            $table->decimal('cost', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('previous_cost', 10, 2)->default(0);
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('entry_date')->nullable();
            $table->text('reason')->nullable();
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
