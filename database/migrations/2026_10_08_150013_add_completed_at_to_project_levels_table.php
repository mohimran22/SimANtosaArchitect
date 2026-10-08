<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_levels', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('is_completed');
        });

        // data lama: anggap selesai hari ini supaya badge tidak langsung merah semua
        DB::table('project_levels')
            ->where('is_completed', true)
            ->update(['completed_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('project_levels', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
    }
};
