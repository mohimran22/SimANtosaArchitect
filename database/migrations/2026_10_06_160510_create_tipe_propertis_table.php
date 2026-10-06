<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipe_properti', function (Blueprint $t) {
            $t->id();
            $t->string('nama', 50)->unique();
            $t->timestamps();
        });

        // isi awal: 4 tipe bawaan + tipe apa pun yang sudah terpakai di data properti
        $nama = collect(['rumah', 'tanah', 'ruko', 'apartemen']);
        if (Schema::hasTable('properti_dijual')) {
            $nama = $nama->merge(DB::table('properti_dijual')->distinct()->pluck('tipe'));
        }

        $nama->filter()
            ->map(fn ($n) => mb_strtolower(trim($n)))
            ->unique()
            ->each(fn ($n) => DB::table('tipe_properti')->insert([
                'nama' => $n, 'created_at' => now(), 'updated_at' => now(),
            ]));
    }

    public function down(): void
    {
        Schema::dropIfExists('tipe_properti');
    }
};