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
Schema::create('project_task_files', function (Blueprint $table) {
    $table->id();
    $table->uuid('project_task_id');
    $table->string('file_path');
    $table->timestamps();

    $table->foreign('project_task_id')
        ->references('id')
        ->on('project_tasks')
        ->cascadeOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_task_files');
    }
};
