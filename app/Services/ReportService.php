<?php

namespace App\Services;

use Illuminate\Support\Collection;

class ReportService
{
    private static function getVal($sub, string $key): float
    {
        if ($sub instanceof Collection) {
            return $sub->sum(fn($item) => self::getVal($item, $key));
        }
        if (is_array($sub)) {
            return $sub[$key] ?? 0;
        }
        if (is_object($sub)) {
            return $sub->$key ?? 0;
        }
        return 0;
    }

    public static function calculateBalanceSheet(Collection|array $groupedAccounts): array
    {
        if ($groupedAccounts instanceof Collection) {
            $groupedAccounts = $groupedAccounts->toArray();
        }
        // 🔹 AKTIVA
        $asetLancar = collect($groupedAccounts['AKTIVA']['Aset Lancar - Kas & Bank'] ?? [])
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));

        $persediaan = collect($groupedAccounts['AKTIVA']['Aset Lancar - Persediaan Barang'] ?? [])
    
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));

        $piutang = collect($groupedAccounts['AKTIVA']['Aset Lancar - Piutang'] ?? [])
    
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));

        $dana = collect($groupedAccounts['AKTIVA']['Aset Lancar - Dana Belum Disetor'] ?? [])
    
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));

        $pajak = collect($groupedAccounts['AKTIVA']['Aset Lancar - Pajak Bayar Dimuka'] ?? [])
    
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));

        $asetTetap = collect($groupedAccounts['AKTIVA']['Aset Tetap'] ?? [])
    
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));

        $penyusutan = collect($groupedAccounts['AKTIVA']['Penyusutan'] ?? [])
            
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));

        $totalAktiva =
            $asetLancar
            + $persediaan
            + $piutang
            + $dana
            + $pajak
            + $asetTetap
            - $penyusutan;


        // 🔹 PASSIVA
        $kewajiban = collect($groupedAccounts['KEWAJIBAN'] ?? [])
            
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));

        $ekuitas = collect($groupedAccounts['EKUITAS'] ?? [])
        
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));

        $pendapatan = collect($groupedAccounts['PENDAPATAN'] ?? [])
            
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));
        $beban = collect($groupedAccounts['BEBAN'] ?? [])
            
            ->sum(fn($sub) => self::getVal($sub, 'subtotalBalance'));
        $labaBerjalan = $pendapatan - $beban;
        $totalPassiva = $kewajiban + $ekuitas + $labaBerjalan;


        return [
            'asetLancar'         => $asetLancar,
            'persediaan'         => $persediaan,
            'piutang'            => $piutang,
            'dana'               => $dana,
            'pajak'              => $pajak,
            'asetTetap'          => $asetTetap,
            'penyusutan'         => $penyusutan,
            'beban'              => $beban,
            'totalAktiva'        => $totalAktiva,
            'labaBerjalan' => $labaBerjalan,
            'kewajiban'     => $kewajiban,
            'ekuitas'       => $ekuitas,
            'pendapatan'    => $pendapatan,
            'totalPassiva'  => $totalPassiva,
        ];
    }
}
