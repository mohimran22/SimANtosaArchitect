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
        Schema::create('build_termins', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->unsignedInteger('termin_no');

            $table->decimal('percentage', 5, 2);

            $table->decimal('amount', 15, 2);

            $table->string('description')->nullable();

            $table->timestamps();

            $table->unique([
                'project_id',
                'termin_no',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('build_termins');
    }
};
