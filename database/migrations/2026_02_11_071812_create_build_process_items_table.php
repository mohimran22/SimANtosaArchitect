<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('build_process_items', function (Blueprint $t) {
            $t->id();

            $t->foreignUuid('project_id')
              ->constrained()
              ->cascadeOnDelete();

            $t->foreignId('rab_item_id')
              ->nullable(); // refer ke rab_items kalau ada

            $t->string('uraian');
            $t->decimal('volume', 14, 2)->nullable();
            $t->string('satuan', 50)->nullable();

            // bobot kurva S
            $t->decimal('bobot_percent', 6, 2)->default(0);

            // schedule rencana
            $t->integer('plan_week_start')->nullable();
            $t->integer('plan_week_end')->nullable();

            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('build_process_items');
    }
};
