<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComposablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('composables', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->json('configuration_rules')->nullable();
            $table->integer('min_ingredients')->nullable();
            $table->integer('max_ingredients')->nullable();
            $table->boolean('base_required')->default(false);
            $table->json('allergens')->nullable();
            $table->string('portion_size')->nullable();
            $table->string('portion_unit')->nullable();
            $table->integer('base_price');
            $table->string('preparation_instructions')->nullable();
            $table->timestamp('preparation_time')->nullable();
            $table->decimal('price', 8, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('composables');
    }
}
