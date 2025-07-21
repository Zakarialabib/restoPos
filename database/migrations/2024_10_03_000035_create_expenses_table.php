<?php

declare(strict_types=1);

use App\Models\CashRegister;
use App\Models\ExpenseCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('category');
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('payment_method');
            $table->string('reference_number')->nullable();
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->foreignUuid('created_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignIdFor(ExpenseCategory::class, 'category_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignIdFor(CashRegister::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
