<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('composable_juices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('image_url')->nullable();
            $table->text('ingredients');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('composable_juices');
    }
};
