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
        Schema::create('small_heavy', function (Blueprint $table) {
            $table->id();
            $table->string('category',100);
            $table->string('image',100)->nullable();
            $table->string('title',100);
            $table->longText('make');
            $table->longText('model');
            $table->string('year_of_reg',100);
            $table->longText('grade');
            $table->longText('chassis');
            $table->longText('serial')->nullable();
            $table->longText('yom');
            $table->longText('kms');
            $table->longText('hrs')->nullable();
            $table->longText('engine');
            $table->longText('transmission')->nullable();
            $table->longText('fuel');
            $table->longText('dimensions')->nullable();
            $table->longText('price');
            $table->Text('price_ru')->nullable();
            $table->Text('price_jpy')->nullable();
            $table->string('sell_points',100);
            $table->longText('remarks');
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_ru_market');
            $table->tinyInteger('is_na_market');
            $table->longText('hooks');
            $table->longText('boom');
            $table->Text('jib')->nullable();
            $table->Text('outrigger')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('small_heavy');
    }
};
