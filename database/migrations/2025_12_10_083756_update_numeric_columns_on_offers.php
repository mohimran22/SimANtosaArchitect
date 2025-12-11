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
            // Ubah semua kolom numeric ke NUMERIC(18,2)
            $table->decimal('price_meter', 18, 2)->change();
            $table->decimal('total_price', 18, 2)->change();
            $table->decimal('discount', 18, 2)->nullable()->change();
            $table->decimal('total_tax', 18, 2)->nullable()->change();
            $table->decimal('grand_total', 18, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->decimal('price_meter', 8, 2)->change();
            $table->decimal('total_price', 8, 2)->change();
            $table->decimal('discount', 8, 2)->nullable()->change();
            $table->decimal('total_tax', 8, 2)->nullable()->change();
            $table->decimal('grand_total', 8, 2)->change();
    }
};
