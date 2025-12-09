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

        $menus = [
            
            [
                'text' => 'Proyek Build',
                        'icon' => 'ti ti-building-community',
                        'url' => '/project/buid',
                        'type' => 'url',
                        'order' => 3,
                        'is_active' => true,
                        'permission_name' => 'lihat daftar proyek',
            ],
            [
                'text' => 'Tenaga',
                        'icon' => 'ti ti-building-community',
                        'url' => '/project/labor',
                        'type' => 'url',
                        'order' => 3,
                        'is_active' => true,
                        'permission_name' => 'lihat daftar proyek',
            ],
            
        ];

        DB::table('menus')->insert($menus);
    }
}
