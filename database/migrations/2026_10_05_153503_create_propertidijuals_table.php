<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('properti_dijual', function (Blueprint $t) {
            $t->id();
            $t->string('judul');
            $t->string('slug')->unique();
            $t->string('status', 20)->default('dijual');   // dijual | disewa | terjual
            $t->string('tipe', 30)->default('rumah');      // rumah | tanah | ruko | apartemen
            $t->unsignedBigInteger('harga')->default(0);
            $t->string('cicilan')->nullable();             // contoh: Rp 7,12 juta/bln
            $t->string('kota');
            $t->string('lokasi');
            $t->text('deskripsi')->nullable();
            $t->unsignedSmallInteger('kt')->nullable();    // kamar tidur
            $t->unsignedSmallInteger('km')->nullable();    // kamar mandi
            $t->unsignedInteger('lt')->nullable();         // luas tanah (m2)
            $t->unsignedInteger('lb')->nullable();         // luas bangunan (m2)
            $t->string('foto')->nullable();                // foto utama
            $t->json('galeri')->nullable();                // foto tambahan
            $t->string('agen_nama')->default('Antosa Architect');
            $t->string('agen_peran')->default('Pemilik Properti');
            $t->string('agen_telepon', 20)->nullable();
            $t->string('agen_foto')->nullable();
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properti_dijual');
    }
};