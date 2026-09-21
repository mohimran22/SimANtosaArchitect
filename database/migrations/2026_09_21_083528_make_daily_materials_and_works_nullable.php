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
        Schema::table('build_daily_materials', function (Blueprint $table) {
            $table->string('nama_bahan')->nullable()->change();
        });

        Schema::table('build_daily_works', function (Blueprint $table) {
            $table->string('satuan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('build_daily_materials', function (Blueprint $table) {
            $table->string('nama_bahan')->nullable(false)->change();
        });

        Schema::table('build_daily_works', function (Blueprint $table) {
            $table->string('satuan')->nullable(false)->change();
        });
    }
};
