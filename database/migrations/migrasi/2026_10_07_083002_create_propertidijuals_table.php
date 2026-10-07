<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properti_dijual', function (Blueprint $table) {
            $table->string('maps_url', 500)->nullable()->after('lokasi');           // link Google Maps (diisi manual)
            $table->unsignedTinyInteger('jumlah_lantai')->nullable()->after('lb');
            $table->string('sertifikat', 30)->nullable()->after('jumlah_lantai');   // SHM, HGB, dst.
            $table->string('perabotan', 30)->nullable()->after('sertifikat');       // unfurnished, semi furnished, furnished
            $table->json('fasilitas')->nullable()->after('deskripsi');
            $table->string('video_url', 500)->nullable()->after('fasilitas');
        });
    }

    public function down(): void
    {
        Schema::table('properti_dijual', function (Blueprint $table) {
            $table->dropColumn(['maps_url', 'jumlah_lantai', 'sertifikat', 'perabotan', 'fasilitas', 'video_url']);
        });
    }
};