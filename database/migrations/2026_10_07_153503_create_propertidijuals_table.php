<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('properti_dijual', function (Blueprint $t) {
            // agen = karyawan Antosa yang menambahkan listing (employees.id bertipe UUID)
            $t->foreignUuid('employee_id')->nullable()->after('lb')
              ->constrained('employees')->nullOnDelete();

            $t->dropColumn(['agen_nama', 'agen_peran', 'agen_telepon', 'agen_foto']);
        });
    }

    public function down(): void
    {
        Schema::table('properti_dijual', function (Blueprint $t) {
            $t->dropConstrainedForeignId('employee_id');

            $t->string('agen_nama')->default('Antosa Architect');
            $t->string('agen_peran')->default('Pemilik Properti');
            $t->string('agen_telepon', 20)->nullable();
            $t->string('agen_foto')->nullable();
        });
    }
};