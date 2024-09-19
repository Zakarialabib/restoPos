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
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->decimal('total_amount', 10, 2);
            $table->integer('status')->default(OrderStatus::Pending);
            $table->timestamps();
            $table->softDeletes();
            // Add index for performance
            $table->index('customer_phone'); // Index on customer_phone for faster queries
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
