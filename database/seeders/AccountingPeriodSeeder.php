<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\AccountingPeriod;
use App\Models\User;
use App\Models\License;

class AccountingPeriodSeeder extends Seeder
{
    public function run(): void
    {
        $licenseId = License::first()->id ?? Str::uuid();
        $userId = User::first()->id ?? Str::uuid();

        $periods = [
            [
                'id' => Str::uuid(),
                'license_id' => $licenseId,
                'period_name' => '202507',
                'is_closed' => false,
                'closed_by' => $userId,
            ],
            [
                'id' => Str::uuid(),
                'period_name' => '202506',
                'is_closed' => true,
                'closed_by' => $userId, // contoh user id
            ],
        ];

        foreach ($periods as $period) {
            AccountingPeriod::updateOrCreate(
                ['period_name' => $period['period_name']],
                [
                    'id' => $period['id'],
                    'license_id' => $period['license_id'],
                    'is_closed' => $period['is_closed'],
                    'closed_by' => $period['closed_by'],
                ]
            );
        }
    }
}
