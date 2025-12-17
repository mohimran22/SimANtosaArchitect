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
        Schema::table('offers', function (Blueprint $table) { 
            $table->string('contract_number')->nullable()->after('notes');
            $table->date('contract_date')->nullable()->after('contract_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {

            // Hapus kolom boolean baru
            $table->dropColumn([
                'contract_number',
                'contract_date',
            ]);
        });
    }
};
