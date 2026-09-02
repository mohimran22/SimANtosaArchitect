<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_justifications', function (Blueprint $table) {
            $table->id();

            // Project
            $table->uuid('project_id');

            // Informasi pekerjaan
            $table->string('contact_name');
            $table->string('job_location');
            $table->string('job_duration')->nullable();

            // Perhitungan RAB
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 30, 10)->default(0);
            $table->decimal('subtotal_after_discount', 15, 2)->default(0);

            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);

            $table->decimal('shipping', 15, 2)->default(0);

            $table->decimal('profit', 5, 2)->default(0);
            $table->decimal('overhead', 5, 2)->default(0);

            $table->decimal('grand_total', 15, 2)->default(0);

            // Nilai dasar sebelum markup
            $table->decimal('base_subtotal', 15, 2)->default(0);

            // Catatan
            $table->text('notes')->nullable();

            // Analisa
            $table->unsignedBigInteger('analisa_version')->nullable();

            // Audit
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_justifications');
    }
};