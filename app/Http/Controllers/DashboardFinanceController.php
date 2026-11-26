<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardFinanceController extends Controller
{
    /**
     * Awalan account_code yang dihitung sebagai Cost (HPP + beban).
     * Sesuaikan dengan bagan akun Anda.
     */
    private const COST_PREFIX = ['5', '6'];

    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->year);

        $omsetLalu = $this->omsetPerBulan($year - 1); // pembanding
        $omsetIni  = $this->omsetPerBulan($year);     // realisasi
        $costLalu  = $this->costPerBulan($year - 1);  // pembanding
        $costIni   = $this->costPerBulan($year);      // realisasi
        $targets   = DB::table('finance_targets')
            ->where('year', $year)
            ->pluck('target', 'month');

        $now  = now();
        $rows = [];

        for ($m = 1; $m <= 12; $m++) {
            $masaDepan = Carbon::create($year, $m, 1)->startOfMonth()->gt($now);

            $oLalu  = (float) ($omsetLalu[$m] ?? 0);
            $target = (float) ($targets[$m] ?? 0);
            $omset  = $masaDepan ? null : (float) ($omsetIni[$m] ?? 0);

            $cLalu  = (float) ($costLalu[$m] ?? 0);
            $cost   = $masaDepan ? null : (float) ($costIni[$m] ?? 0);

            $rows[$m] = $this->hitung($oLalu, $target, $omset);
            $rows[$m]['label'] = Carbon::create($year, $m, 1)->translatedFormat('M Y');

            // --- Cost & Profit ---
            $rows[$m]['cost_lalu']   = $cLalu;
            $rows[$m]['cost']        = $cost;
            $rows[$m]['rasio_cost']  = ($cost !== null && $omset > 0) ? $cost / $omset * 100 : null;
            $rows[$m]['cost_tumbuh'] = ($cost !== null && $cLalu > 0) ? $cost / $cLalu * 100 : null;
            $rows[$m]['profit']      = $cost !== null ? $omset - $cost : null;
            $rows[$m]['margin']      = ($cost !== null && $omset > 0) ? ($omset - $cost) / $omset * 100 : null;
        }

        // ---------- Kolom total tahun ----------
        $terisi     = collect($rows)->whereNotNull('omset');   // bulan yang sudah berjalan
        $totalOmset = $terisi->isEmpty() ? null : $terisi->sum('omset');

        $total = $this->hitung(
            collect($rows)->sum('omset_lalu'),
            collect($rows)->sum('target'),
            $totalOmset
        );

        $totalCost   = $terisi->isEmpty() ? null : $terisi->sum('cost');
        $costLaluYtd = $terisi->sum('cost_lalu'); // dibandingkan hanya dengan bulan yang sudah berjalan

        $total['cost_lalu']   = collect($rows)->sum('cost_lalu');
        $total['cost']        = $totalCost;
        $total['rasio_cost']  = ($totalCost !== null && $totalOmset > 0) ? $totalCost / $totalOmset * 100 : null;
        $total['cost_tumbuh'] = ($totalCost !== null && $costLaluYtd > 0) ? $totalCost / $costLaluYtd * 100 : null;
        $total['profit']      = $totalCost !== null ? $totalOmset - $totalCost : null;
        $total['margin']      = ($totalCost !== null && $totalOmset > 0) ? ($totalOmset - $totalCost) / $totalOmset * 100 : null;

        return view('dashboard.finance', [
            'year'  => $year,
            'rows'  => $rows,
            'total' => $total,
        ]);
    }

    /**
     * Rumus sesuai sketsa:
     *  Progress    = omset / target * 100
     *  Pertumbuhan = omset / omset tahun lalu * 100
     */
    private function hitung(float $omsetLalu, float $target, ?float $omset): array
    {
        return [
            'omset_lalu'  => $omsetLalu,
            'target'      => $target,
            'omset'       => $omset,
            'progress'    => ($omset !== null && $target > 0) ? $omset / $target * 100 : null,
            'pertumbuhan' => ($omset !== null && $omsetLalu > 0) ? $omset / $omsetLalu * 100 : null,
        ];
    }

    /** Omset per bulan: akun Pendapatan (kode diawali "4"), nilai = kredit - debit. */
    private function omsetPerBulan(int $year)
    {
        return $this->nilaiAkunPerBulan($year, ['4'], 'kredit');
    }

    /** Cost per bulan: akun HPP/beban (lihat COST_PREFIX), nilai = debit - kredit. */
    private function costPerBulan(int $year)
    {
        return $this->nilaiAkunPerBulan($year, self::COST_PREFIX, 'debit');
    }

    /**
     * Total per bulan (key 1..12) dari jurnal untuk akun berawalan tertentu.
     * 'kredit' = kredit - debit (pendapatan), 'debit' = debit - kredit (biaya).
     */
    private function nilaiAkunPerBulan(int $year, array $prefix, string $sisi)
    {
        $nilai = $sisi === 'kredit'
            ? 'SUM(d.credit - d.debit)'
            : 'SUM(d.debit - d.credit)';

        return DB::table('accounting_journal_details as d')
            ->join('accounting_journals as j', 'j.id', '=', 'd.journal_id')
            ->join('accounting_accounts as a', 'a.id', '=', 'd.account_id')
            ->where(function ($q) use ($prefix) {
                foreach ($prefix as $p) {
                    $q->orWhere('a.account_code', 'like', $p . '%');
                }
            })
            ->where('j.transaction_date', '>=', "$year-01-01")
            ->where('j.transaction_date', '<', ($year + 1) . '-01-01')
            ->selectRaw("EXTRACT(MONTH FROM j.transaction_date)::int as bulan, $nilai as total")
            ->groupByRaw('EXTRACT(MONTH FROM j.transaction_date)')
            ->pluck('total', 'bulan')
            ->map(fn ($v) => (float) $v);
    }
}