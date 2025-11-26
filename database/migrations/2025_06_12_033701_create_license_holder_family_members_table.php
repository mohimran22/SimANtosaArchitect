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
        Schema::create('license_holder_family_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('license_holder_id');
            $table->foreign('license_holder_id')->references('id')->on('license_holders')->onDelete('cascade');
            $table->string('name');
            $table->unsignedTinyInteger('relationship');
            $table->unsignedtinyInteger('gender');
            $table->string('birth_date', 10);
            $table->string('job')->nullable();
            $table->string('job_phone')->nullable();
            $table->string('last_education_level')->nullable();
            $table->string('institution_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_holder_family_members');
    }
};
