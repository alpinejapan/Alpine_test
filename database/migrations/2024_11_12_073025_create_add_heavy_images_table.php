<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('add_heavy_images', function (Blueprint $table) {
            $table->id();
            $table->String('image',200);
            $table->String('category',100);
            $table->tinyInteger('is_active')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_heavy_images');
    }
};
