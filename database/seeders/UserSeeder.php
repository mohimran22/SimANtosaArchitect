<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $permissions = [
            [
                'id' => Str::uuid(),
                'name' => 'tambah data kategori',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Kategori',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'lihat daftar kategori',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Kategori',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'ubah data kategori',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Kategori',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'hapus data kategori',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Kategori',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'tambah data merk',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Merk',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'lihat daftar merk',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Merk',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'ubah data merk',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Merk',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'hapus data merk',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Merk',
            ],
                      [
                'id' => Str::uuid(),
                'name' => 'tambah data tipe',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Tipe',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'lihat daftar tipe',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Tipe',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'ubah data tipe',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Tipe',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'hapus data tipe',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
                'modules' => 'Tipe',
            ],
            
        ];

        DB::table('permissions')->insert($permissions);
    }
}
