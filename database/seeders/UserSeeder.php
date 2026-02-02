<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@antosaarchitect.com'],
            [
                'id' => Str::uuid(),
                'fullname' => 'Super Admin',
                'nickname' => 'Admin',
                'gender' => 'L',
                'password' => Hash::make('password123'),
                'phone' => '08123456789',
            ]
        );

        $role = Role::where('name', 'Super-Admin')->first();

        if ($role) {
            $user->assignRole($role);
        }
    }
}
