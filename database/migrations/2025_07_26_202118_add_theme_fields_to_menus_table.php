<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table): void {
            $table->json('permissions')->nullable()->after('description');
            $table->boolean('is_dynamic')->default(false)->after('permissions');
            $table->string('component_type')->nullable()->after('is_dynamic');
            $table->json('meta_data')->nullable()->after('component_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table): void {
            $table->dropColumn(['permissions', 'is_dynamic', 'component_type', 'meta_data']);
        });
    }
};
