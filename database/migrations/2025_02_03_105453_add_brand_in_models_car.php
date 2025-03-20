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
        Schema::table('models_cars', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->after('category');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('models_car', function (Blueprint $table) {
            //
        });
    }
};
