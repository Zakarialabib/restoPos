<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Models\CashRegister;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('reference')->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('refunded_amount', 10, 2)->default(0);
            $table->string('status')->default(OrderStatus::Pending->value);
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->text('preparation_notes')->nullable();
            $table->string('source')->nullable();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(CashRegister::class)->nullable()->constrained()->nullOnDelete();
            $table->string('guest_token', 32)->nullable();
            $table->string('priority')->default('normal');
            $table->text('kitchen_notes')->nullable();

            // Indexes for better performance
            $table->index('guest_token');
            $table->index('reference');
            $table->index('status');
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index('delivery_date');
            $table->index(['status', 'created_at']);
            $table->index(['customer_email', 'created_at']);
            $table->index(['payment_status', 'created_at']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
