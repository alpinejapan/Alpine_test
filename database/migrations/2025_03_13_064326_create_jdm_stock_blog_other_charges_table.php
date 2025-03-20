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
        Schema::create('jdm_stock_blog_other_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('jdm_blog_id');
            $table->integer('marine_insurance_value');
            $table->integer('inland_inspection_value');
            $table->timestamps();

            $table->foreign('jdm_blog_id')
            ->references('id')
            ->on('blog')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jdm_stock_blog_other_charges');
    }
};
