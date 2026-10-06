<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('properti_dijual_fotos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('properti_dijual_id')->constrained('properti_dijual')->cascadeOnDelete();
            $t->string('path');
            $t->string('keterangan')->nullable();
            $t->unsignedInteger('urutan')->default(0);
            $t->timestamps();
        });

        // pindahkan data lama dari kolom JSON `galeri` (kalau ada), lalu hapus kolomnya
        if (Schema::hasColumn('properti_dijual', 'galeri')) {
            DB::table('properti_dijual')->whereNotNull('galeri')->orderBy('id')->each(function ($row) {
                foreach ((json_decode($row->galeri, true) ?: []) as $i => $path) {
                    DB::table('properti_dijual_fotos')->insert([
                        'properti_dijual_id' => $row->id,
                        'path'               => $path,
                        'urutan'             => $i,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                }
            });

            Schema::table('properti_dijual', function (Blueprint $t) {
                $t->dropColumn('galeri');
            });
        }
    }

    public function down(): void
    {
        Schema::table('properti_dijual', function (Blueprint $t) {
            $t->json('galeri')->nullable();
        });
        Schema::dropIfExists('properti_dijual_fotos');
    }
};