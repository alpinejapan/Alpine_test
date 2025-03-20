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
        Schema::create('jdm_stock_heavy_other_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jdm_heavy_id');
            $table->integer('marine_insurance_value');
            $table->integer('inland_inspection_value');
            $table->timestamps();

            $table->foreign('jdm_heavy_id')
            ->references('id')
            ->on('heavy')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jdm_stock_heavy_other_charges');
    }
};
