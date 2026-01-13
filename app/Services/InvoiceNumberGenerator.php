<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class InvoiceNumberGenerator
{
    public static function generate(string $type): string
    {
        return DB::transaction(function () use ($type) {

            $now = now();
            $tahunFull = $now->year;
            $tahun = $now->format('y');
            $bulanRomawi = \App\Helpers\GeneralHelper::bulanRomawi($now->month);

            switch ($type) {
                case Invoice::TYPE_SURVEY:
                    $prefix = "INV/SRV/$tahun/$bulanRomawi";
                    break;

                case Invoice::TYPE_DP:
                    $prefix = "INV/DSN/A/$tahun/$bulanRomawi";
                    break;

                case Invoice::TYPE_FINAL:
                    $prefix = "INV/DSN/B/$tahun/$bulanRomawi";
                    break;

                case Invoice::TYPE_RAB:
                    $prefix = "INV/RAB/$tahun/$bulanRomawi";
                    break;

                default:
                    throw new \Exception("Tipe invoice tidak dikenal");
            }

            // 🔒 AMBIL DATA TERAKHIR DENGAN LOCK
            $last = Invoice::where('invoice_number', 'like', $prefix . '/%')
                ->whereYear('invoice_date', $tahunFull)
                ->lockForUpdate()
                ->orderByDesc('invoice_number')
                ->first();

            if ($last) {
                $explode = explode('/', $last->invoice_number);
                $lastNumber = (int) end($explode);
                $next = $lastNumber + 1;
            } else {
                $next = 1;
            }

            $nomorUrut = str_pad($next, 3, '0', STR_PAD_LEFT);

            return $prefix . '/' . $nomorUrut;
        });
    }
}