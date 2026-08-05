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
        Schema::create('attendance_requests', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->foreignUuid('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->enum('request_type', [
                'permission',
                'sick',
                'leave',
                'business_trip',
            ]);

            $table->text('reason')->nullable();

            $table->string('attachment')->nullable();

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

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_requests');
    }
};
