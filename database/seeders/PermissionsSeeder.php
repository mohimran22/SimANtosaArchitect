<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear Spatie permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

          // Bersihkan dulu pivot biar FK aman
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        // Daftar permission
        $permissions = [
            'lisensi.tambah',
            'lisensi.lihat',
            'lisensi.ubah',
            'lisensi.hapus',

            'pemilik-lisensi.tambah',
            'pemilik-lisensi.lihat',
            'pemilik-lisensi.ubah',
            'pemilik-lisensi.hapus',

            'keluarga-pemilik.tambah',
            'keluarga-pemilik.lihat',
            'keluarga-pemilik.ubah',
            'keluarga-pemilik.hapus',

            'pendidikan-pemilik.tambah',
            'pendidikan-pemilik.lihat',
            'pendidikan-pemilik.ubah',
            'pendidikan-pemilik.hapus',

            'pekerjaan-pemilik.tambah',
            'pekerjaan-pemilik.lihat',
            'pekerjaan-pemilik.ubah',
            'pekerjaan-pemilik.hapus',
        ];

        foreach ($permissions as $permissionName) {
    $permission = Permission::firstOrCreate(
        ['name' => $permissionName, 'guard_name' => 'web'],
        ['id' => (string) Str::uuid()]
    );

    // Paksa id kalau masih numeric atau salah format
    if (!$permission->id || strlen($permission->id) < 36) {
        $permission->id = Str::uuid();
        $permission->save();
    }
}


        // Buat roles
        $roles = [
            'Super-Admin',
            'Pemilik Lisensi',
            'HRD',
            'Akuntan',
            'Karyawan',
        ];

        foreach ($roles as $roleName) {
    $role = Role::firstOrCreate(
        ['name' => $roleName, 'guard_name' => 'web'],
        ['id' => (string) Str::uuid()]
    );

    // Kalau role ketemu tapi id nya bukan UUID → update!
    if (!$role->id || strlen($role->id) < 36) {
        $role->id = Str::uuid();
        $role->save();
    }
}


        // Assign permissions
        $superAdmin = Role::where('name', 'Super-Admin')->first();
        $superAdmin->syncPermissions(Permission::all());

        Role::where('name', 'Pemilik Lisensi')->first()->syncPermissions([
            'lisensi.ubah',
            'pemilik-lisensi.ubah',
            'keluarga-pemilik.tambah',
            'keluarga-pemilik.lihat',
            'keluarga-pemilik.ubah',
            'keluarga-pemilik.hapus',
            'pendidikan-pemilik.tambah',
            'pendidikan-pemilik.lihat',
            'pendidikan-pemilik.ubah',
            'pendidikan-pemilik.hapus',
            'pekerjaan-pemilik.tambah',
            'pekerjaan-pemilik.lihat',
            'pekerjaan-pemilik.ubah',
            'pekerjaan-pemilik.hapus',
        ]);

        Role::where('name', 'HRD')->first()->syncPermissions([]);
        Role::where('name', 'Akuntan')->first()->syncPermissions([]);
        Role::where('name', 'Karyawan')->first()->syncPermissions([]);

        // Buat contoh user + role
        \App\Models\User::factory()->create([
            'name' => 'User Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('Super-Admin');

        \App\Models\User::factory()->create([
            'name' => 'User Pemilik Lisensi',
            'email' => 'pemilik@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('Pemilik Lisensi');

        \App\Models\User::factory()->create([
            'name' => 'User HRD',
            'email' => 'hrd@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('HRD');

        \App\Models\User::factory()->create([
            'name' => 'User Akuntan',
            'email' => 'akuntan@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('Akuntan');

        \App\Models\User::factory()->create([
            'name' => 'User Karyawan',
            'email' => 'karyawan@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('Karyawan');
    }
}
