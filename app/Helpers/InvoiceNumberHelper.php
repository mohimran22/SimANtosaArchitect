<?php

namespace App\Helpers;

use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceNumberHelper
{
    public static function survey()
    {
        $now = Carbon::now();

        $bulanRomawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        $month = $bulanRomawi[$now->month];
        $year  = $now->year;

        $count = Invoice::where('invoice_type', 'survey')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count() + 1;

        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "INV-SRV/ANT/{$sequence}/{$month}/{$year}";
    }
}
