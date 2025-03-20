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
        Schema::table('blog', function (Blueprint $table) {
            $table->string('location')->nullable();
        });
        Schema::table('heavy', function (Blueprint $table) {
            $table->string('location')->nullable();
        });
        Schema::table('commercial', function (Blueprint $table) {
            $table->string('location')->nullable();
        });
        Schema::table('small_heavy', function (Blueprint $table) {
            $table->string('location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
