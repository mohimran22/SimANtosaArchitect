<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_overtimes', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->foreignUuid('attendance_id')
                ->constrained()
                ->cascadeOnDelete();

            // Mulai lembur
            $table->timestamp('start_time')->nullable();
            $table->string('start_photo')->nullable();
            $table->decimal('start_lat', 10, 7)->nullable();
            $table->decimal('start_lng', 10, 7)->nullable();

            // Selesai lembur
            $table->timestamp('end_time')->nullable();
            $table->string('end_photo')->nullable();
            $table->decimal('end_lat', 10, 7)->nullable();
            $table->decimal('end_lng', 10, 7)->nullable();

            // Hasil perhitungan
            $table->integer('work_minutes')->default(0);

            // Pengajuan
            $table->text('reason')->nullable();

            // Jenis lembur
            $table->enum('type', [
                'weekday',
                'holiday',
            ])->default('weekday');

            // Approval
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->foreignUuid('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->text('approval_note')->nullable();

            // Perhitungan upah
            $table->decimal('hourly_rate', 15, 2)->nullable();
            $table->decimal('overtime_pay', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_over_times');
    }
};
