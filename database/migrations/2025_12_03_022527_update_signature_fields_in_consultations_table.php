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
        Schema::table('consultations', function (Blueprint $table) {
            // Hapus kolom lama untuk upload file
            if (Schema::hasColumn('consultations', 'consultant_signature')) {
                $table->dropColumn('consultant_signature');
            }

            if (Schema::hasColumn('consultations', 'client_signature')) {
                $table->dropColumn('client_signature');
            }

            // Tambah kolom tanda tangan digital
            $table->boolean('consultant_signed')->default(false);
            $table->boolean('client_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {

            // Kembalikan kolom lama
            $table->string('consultant_signature')->nullable();
            $table->string('client_signature')->nullable();

            // Hapus kolom boolean baru
            $table->dropColumn([
                'consultant_signed',
                'client_signed',
                'signed_at',
            ]);
        });
    }
};
