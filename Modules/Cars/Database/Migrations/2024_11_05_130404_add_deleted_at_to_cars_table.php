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
            $table->softDeletes();
        });
        Schema::table('heavy', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('small_heavy', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('auct_lots_xml_jp', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('auct_lots_xml_jp_op', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog', function (Blueprint $table) {
            //
        });
    }
};
