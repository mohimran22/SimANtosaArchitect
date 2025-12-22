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
        Schema::table('project_tasks', function (Blueprint $table) {
        $table->uuid('rejected_by')->nullable();
        $table->foreign('rejected_by')->references('id')->on('users')->cascadeOnDelete();
        $table->timestamp('rejected_at')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('project_tasks', function (Blueprint $table) {
        $table->dropColumn('rejected_at');
        $table->dropColumn('rejected_by');
    });
    }
};
