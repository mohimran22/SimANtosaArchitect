<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Religion;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\PostalCode;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id' => Str::uuid(),

            'fullname' => 'Super Admin',
            'nickname' => 'Admin',
            'gender' => 1,

            'email' => 'superadmin@gmail.com',
            'email_verified_at' => now(),

            'password' => Hash::make('password'),

            'birth_place' => 'Jakarta',
            'identity_number' => '1234567890123456',
            'birth_date' => '1990-01-01',

            // ambil FK pertama supaya aman
            'religion_id' => Religion::first()->id ?? 1,
            'province_id' => 5,
            'city_id' => 91,
            'district_id' => 1104,
            'sub_district_id' => 15935,
            'postal_code_id' => 15935,

            'address' => 'Jl. Contoh Alamat No. 1',
            'phone' => '08123456789',

            'photo' => null,
            'identity_photo' => null,

            'remember_token' => Str::random(10),
        ]);
    }
}
