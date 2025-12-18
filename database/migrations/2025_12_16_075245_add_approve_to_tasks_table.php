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
        $table->uuid('approved_by')->nullable();
        $table->foreign('approved_by')->references('id')->on('users')->cascadeOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    // Schema::table('project_tasks', function (Blueprint $table) {
    //     $table->dropColumn('approval_status');
    //     $table->dropColumn('reject_note');
    //     $table->dropColumn('approved_at');
    //     $table->dropColumn('is_revision');
    // });
    }
};
