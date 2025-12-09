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
        Schema::create('survey_employees', function (Blueprint $table) {
            $table->uuid('employee_id')->index();
            $table->uuid('survey_id')->index();

            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
        
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unique(['employee_id', 'survey_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_employees');
    }
};
