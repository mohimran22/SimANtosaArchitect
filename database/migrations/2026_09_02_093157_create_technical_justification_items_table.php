<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_justification_items', function (Blueprint $table) {
            $table->id();

            // Relasi ke RAB
            $table->unsignedBigInteger('technical_justification_id');
            
            // Identitas pekerjaan
            $table->string('floor_name')->nullable();
            $table->string('category_name')->nullable();

            $table->string('job_name');

            $table->text('description')->nullable();

            // Volume & satuan
            $table->decimal('volume', 15, 5);
            $table->string('satuan');

            // Harga
            $table->decimal('price', 15, 2);
            $table->decimal('total', 18, 2);

            // Markup item
            $table->decimal('profit', 5, 2)->default(0);
            $table->decimal('overhead', 5, 2)->default(0);

            // Harga dasar sebelum markup
            $table->decimal('base_price', 15, 2)->nullable();

            // Volume tambahan jika masih digunakan
            $table->decimal('volume1', 8, 2)->nullable();
            $table->decimal('volume2', 8, 5)->nullable();

            // Status draft
            $table->boolean('is_draft')->default(true);

            // Urutan item
            $table->integer('order_no')->default(0);

            $table->timestamps();

            // Foreign key
            $table->foreign('technical_justification_id')
                ->references('id')
                ->on('technical_justifications')
                ->cascadeOnDelete();

            // Index untuk rendering / sorting
            $table->index(
                ['technical_justification_id', 'floor_name', 'category_name'],
                'technical_items_technical_floor_category_idx'
            );

            $table->index(
                ['technical_justification_id', 'order_no'],
                'technical_items_technical_order_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_justification_items');
    }
};