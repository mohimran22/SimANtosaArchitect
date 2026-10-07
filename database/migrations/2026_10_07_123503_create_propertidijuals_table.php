<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('properti_dijual', function (Blueprint $t) {
            // Dibuat tanpa foreign key supaya aman terhadap tipe id tabel wilayah.
            // Kalau id wilayahmu bertipe UUID, ganti unsignedBigInteger menjadi uuid.
            $t->unsignedBigInteger('province_id')->nullable()->after('cicilan');
            $t->unsignedBigInteger('city_id')->nullable()->after('province_id');
            $t->unsignedBigInteger('district_id')->nullable()->after('city_id');
            $t->unsignedBigInteger('sub_district_id')->nullable()->after('district_id');
            $t->unsignedBigInteger('postal_code_id')->nullable()->after('sub_district_id');

            $t->index('city_id');

            // kota diisi otomatis dari nama kota terpilih; lokasi jadi alamat jalan opsional
            $t->string('kota')->nullable()->change();
            $t->string('lokasi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('properti_dijual', function (Blueprint $t) {
            $t->dropIndex(['city_id']);
            $t->dropColumn(['province_id', 'city_id', 'district_id', 'sub_district_id', 'postal_code_id']);
        });
    }
};