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
        Schema::create('auct_lots_xml_jp_op_other_chargers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('auct_id');
            $table->integer('commission_value');
            $table->integer('shipping_value');
            $table->integer('marine_insurance_value');
            $table->integer('inland_inspection_value');
            $table->integer('top_sell');
            $table->integer('new_arrivals');
            $table->timestamps();
        
            // Proper foreign key definition
            $table->foreign('auct_id')
            ->references('id')
            ->on('auct_lots_xml_jp_op')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auct_lots_xml_jp_op_other_chargers');
    }
};
