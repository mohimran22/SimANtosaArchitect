<?php

namespace App\Services;

use App\Models\InvoiceBuild;
use Illuminate\Support\Facades\DB;

class InvoiceBuildNumberGenerator
{
    public static function generate(string $type): string
    {
        return DB::transaction(function () use ($type) {

            $now = now();
            $tahunFull = $now->format('Y');
            $tahun = $now->format('y');
            $bulanRomawi = \App\Helpers\GeneralHelper::bulanRomawi($now->month);

            switch ($type) {
                case InvoiceBuild::TYPE_DP:
                    $prefix = "INV/BLD/A/$tahun/$bulanRomawi";
                    break;

                case InvoiceBuild::TYPE_DP:
                    $prefix = "INV/BLD/B/$tahun/$bulanRomawi";
                    break;

                case InvoiceBuild::TYPE_FINAL:
                    $prefix = "INV/BLD/C/$tahun/$bulanRomawi";
                    break;

                case InvoiceBuild::TYPE_DP:
                    $prefix = "INV/BLD/D/$tahun/$bulanRomawi";
                    break;

                default:
                    throw new \Exception("Tipe invoice tidak dikenal");
            }

            // 🔒 LOCK COUNTER PER PREFIX + TAHUN
            $counter = DB::table('invoice_counters')
                ->where('prefix', $prefix)
                ->where('year', $tahunFull)
                ->lockForUpdate()
                ->first();

            if (!$counter) {
                DB::table('invoice_counters')->insert([
                    'prefix' => $prefix,
                    'year' => $tahunFull,
                    'last_number' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $next = 1;
            } else {
                $next = $counter->last_number + 1;

                DB::table('invoice_counters')
                    ->where('prefix', $prefix)
                    ->where('year', $tahunFull)
                    ->update([
                        'last_number' => $next,
                        'updated_at' => now(),
                    ]);
            }

            $nomorUrut = str_pad($next, 3, '0', STR_PAD_LEFT);

            return $prefix . '/' . $nomorUrut;
        }, 5);
    }
}
