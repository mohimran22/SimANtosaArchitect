<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function () {

            $permissions = [
                'tambah data user',
                'lihat daftar user',
                'ubah data user',
                'hapus data user',
                'tambah data role',
                'lihat daftar role',
                'ubah data role',
                'hapus data role',
                'kelola akun',
                'tambah data karyawan',
                'lihat data karyawan',
                'lihat daftar karyawan',
                'ubah data karyawan',
                'hapus data karyawan',
                'riwayat penggajian karyawan',
                'lihat daftar gudang',
                'tambah data gudang',
                'lihat data gudang',
                'ubah data gudang',
                'hapus data gudang',
                'riwayat transaksi gudang',
                'lihat daftar produk',
                'tambah data produk',
                'lihat data produk',
                'ubah data produk',
                'hapus data produk',
                'riwayat pembelian produk',
                'riwayat penjualan produk',
                'lihat daftar customer',
                'tambah data customer',
                'lihat data customer',
                'ubah data customer',
                'hapus data customer',
                'riwayat transaksi customer',
                'lihat daftar affiliator',
                'tambah data affiliator',
                'lihat data affiliator',
                'ubah data affiliator',
                'hapus data affiliator',
                'riwayat performa affiliator',
                'lihat daftar supplier',
                'tambah data supplier',
                'lihat data supplier',
                'ubah data supplier',
                'hapus data supplier',
                'riwayat pembelian supplier',
                'lihat daftar investor',
                'tambah data investor',
                'lihat data investor',
                'ubah data investor',
                'hapus data investor',
                'saham investor',
                'lihat daftar tukang',
                'tambah data tukang',
                'lihat data tukang',
                'ubah data tukang',
                'hapus data tukang',
                'riwayat penggajian tukang',
                'lihat daftar kontraktor',
                'tambah data kontraktor',
                'lihat data kontraktor',
                'ubah data kontraktor',
                'hapus data kontraktor',
                'riwayat penggajian kontraktor',
                'lihat daftar dokumen',
                'tambah dokumen',
                'lihat dokumen',
                'ubah dokumen',
                'hapus dokumen',
                'lihat daftar pembelian produk',
                'tambah data pembelian produk',
                'lihat data pembelian produk',
                'ubah data pembelian produk',
                'hapus data pembelian produk',
                'persetujuan pembelian produk',
                'riwayat pembelian produk',
                'lihat daftar penjualan produk',
                'tambah data penjualan produk',
                'lihat data penjualan produk',
                'ubah data penjualan produk',
                'hapus data penjualan produk',
                'persetujuan penjualan produk',
                'riwayat penjualan produk',
                'lihat daftar proyek',
                'tambah data proyek',
                'lihat data proyek',
                'ubah data proyek',
                'hapus data proyek',
                'tambah data rab',
                'lihat data rab',
                'ubah data rab',
                'hapus data rab',
                'tambah akun-akuntansi',
                'lihat akun-akuntansi',
                'ubah akun-akuntansi',
                'hapus akun-akuntansi',
                'tambah jurnal',
                'lihat jurnal',
                'ubah jurnal',
                'hapus jurnal',
                'lihat daftar absensi',
                'tambah data absensi',
                'lihat data absensi',
                'ubah data absensi',
                'hapus data absensi',
                'lihat daftar pelatihan',
                'tambah data pelatihan',
                'lihat data pelatihan',
                'ubah data pelatihan',
                'hapus data pelatihan',
                'lihat daftar penilaian kinerja',
                'tambah data penilaian kinerja',
                'lihat data penilaian kinerja',
                'ubah data penilaian kinerja',
                'hapus data penilaian kinerja',
            ];

            foreach ($permissions as $name) {
                Permission::updateOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['modules' => $this->detectModule($name)]
                );
            }

            $roleGroups = [
                'Internal' => [
                    'Super-Admin',
                    'Komisaris',
                    'Direktur',
                    'Manager Administrasi',
                    'Manager Teknik',
                    'Supervisor Marketing',
                    'Supervisor Finance',
                    'Supervisor HRD',
                    'Supervisor Principal Arsitek',
                    'Supervisor Sipil',
                    'Staff Marketing',
                    'Staff Finance',
                    'Staff HRD',
                    'Drafter',
                    'QC',
                    'Estimator',
                ],
                'Eksternal' => [
                    'Investor',
                    'Tukang',
                    'Mitra Kontraktor',
                    'Mitra Supplier',
                    'Mitra Arsitek',
                    'Customer',
                    'Affiliator',
                ],
            ];

            foreach ($roleGroups as $group => $roles) {
                foreach ($roles as $roleName) {
                    Role::updateOrCreate(
                        ['name' => $roleName, 'guard_name' => 'web'],
                        ['group' => $group]
                    );
                }
            }

            $superAdmin = Role::where('name', 'Super-Admin')->first();
            $superAdmin?->syncPermissions(Permission::all());
        });
    }

    private function detectModule(string $permission): string
    {
        return match (true) {
            str_contains($permission, 'karyawan') => 'Karyawan',
            str_contains($permission, 'gudang') => 'Gudang',
            str_contains($permission, 'produk') => 'Produk',
            str_contains($permission, 'customer') => 'Customer',
            str_contains($permission, 'affiliator') => 'Affiliator',
            str_contains($permission, 'supplier') => 'Supplier',
            str_contains($permission, 'investor') => 'Investor',
            str_contains($permission, 'tukang') => 'Tukang',
            str_contains($permission, 'kontraktor') => 'Kontraktor',
            str_contains($permission, 'dokumen') => 'Dokumen',
            str_contains($permission, 'pembelian') => 'Pembelian Produk',
            str_contains($permission, 'penjualan') => 'Penjualan Produk',
            str_contains($permission, 'proyek') => 'Proyek',
            str_contains($permission, 'rab') => 'RAB',
            str_contains($permission, 'akun-akuntansi') => 'Akun Akuntansi',
            str_contains($permission, 'jurnal') => 'Jurnal',
            str_contains($permission, 'user') => 'User',
            str_contains($permission, 'role') => 'Role',
            str_contains($permission, 'absensi') => 'Absensi',
            str_contains($permission, 'pelatihan') => 'Pelatihan',
            str_contains($permission, 'kinerja') => 'Kinerja',
            default => 'Lainnya',
        };
    }
}

        // Role::where('name', 'Komisaris')->first()->syncPermissions([]);
        // Role::where('name', 'Tukang')->first()->syncPermissions([]);
        // Role::where('name', 'Kontraktor')->first()->syncPermissions([]);

        // Buat contoh user + role
        // \App\Models\User::factory()->create([
        //     'fullname' => 'Komisaris',
        //     'email' => 'komisaris@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole('Komisaris');

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Manager Administrasi',
        //     'email' => 'manageradm@example.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole('Manager Administrasi');

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Manager Teknik',
        //     'email' => 'managerteknik@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole('Manager Teknik');

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Spv Marketing',
        //     'email' => 'spvmarketing@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Spv Marketing']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Spv Finance',
        //     'email' => 'spvfinance@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Spv Finance']);
        // \App\Models\User::factory()->create([
        //     'fullname' => 'Spv Arsitek',
        //     'email' => 'spvarsitek@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Spv Arsitek']);
        // \App\Models\User::factory()->create([
        //     'fullname' => 'Spv Sipil',
        //     'email' => 'spvsipil@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Spv Sipil']);
        // \App\Models\User::factory()->create([
        //     'fullname' => 'Staff Marketing',
        //     'email' => 'staffmarketing@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Staff Marketing']);
        // \App\Models\User::factory()->create([
        //     'fullname' => 'Staff Finance',
        //     'email' => 'stafffinance@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Staff Finance']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Staff HRD',
        //     'email' => 'staffhrd@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Staff HRD']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Quality Control',
        //     'email' => 'qc@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['QC']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Estimator',
        //     'email' => 'estimator@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Estimator']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Investor',
        //     'email' => 'investor@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Investor']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Tukang',
        //     'email' => 'worker@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Tukang']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Mitra Kontraktor',
        //     'email' => 'mitrak@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Mitra Kontraktor']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Mitra Supplier',
        //     'email' => 'mitras@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Mitra Supplier']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Mitra Arsitek',
        //     'email' => 'mitraa@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Mitra Arsitek']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Customer',
        //     'email' => 'customer@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Customer']);

        // \App\Models\User::factory()->create([
        //     'fullname' => 'Affiliator',
        //     'email' => 'affiliator@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ])->assignRole(['Affiliator']);
