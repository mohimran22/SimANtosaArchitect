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
        Schema::create('project_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_group_id')->nullable()->index();
            $table->string('name')->nullable();    
            $table->boolean('is_active')->default(true);
            $table->foreign('project_group_id')->references('id')->on('project_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_groups');
    }
};
