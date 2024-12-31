<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default(OrderStatus::Pending->value);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'created_at']);
            $table->index(['customer_email', 'created_at']);
            $table->index(['payment_status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
