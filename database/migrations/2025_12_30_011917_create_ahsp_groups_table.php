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
        Schema::create('ahsp_groups', function (Blueprint $table) {
            $table->id();
            $table->string('bidang', 10);           // PP
            $table->string('kode', 50)->unique();   // A.2.2.1
            $table->string('nama');                 // HARGA SATUAN PEKERJAAN PERSIAPAN
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahsp_groups');
    }
};
