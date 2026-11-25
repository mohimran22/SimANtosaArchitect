<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_contractor', function (Blueprint $table) {
            $table->uuid('project_id');
            $table->uuid('contractor_id');
            $table->timestamps();

            $table->primary(['project_id', 'contractor_id']);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('contractor_id')->references('id')->on('contractors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_contractor');
    }
};
