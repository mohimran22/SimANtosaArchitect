<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technical_justifications', function (Blueprint $table) {
            $table->string('justek_period', 7)
                ->nullable()
                ->after('justek_number');

            $table->index('justek_period');
        });
    }

    public function down(): void
    {
        Schema::table('technical_justifications', function (Blueprint $table) {
            $table->dropIndex([
                'technical_justifications_justek_period_index'
            ]);

            $table->dropColumn('justek_period');
        });
    }
};