<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_revisions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('attendance_id');
            $table->uuid('edited_by');
            $table->timestamp('edited_at');
            $table->text('edit_reason')->nullable();
            $table->jsonb('old_data');
            $table->jsonb('new_data');
            $table->timestamps();
            $table->foreign('attendance_id')
                ->references('id')
                ->on('attendances')
                ->cascadeOnDelete();
            $table->foreign('edited_by')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
            $table->index('attendance_id');
            $table->index('edited_by');
            $table->index('edited_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_revisions');
    }
};
