<?php

namespace App\Services;

use Illuminate\Support\Collection;

class BalanceSheetService
{
    private static function val($data, string $key): float
    {
        if ($data instanceof Collection) {
            return $data->sum(fn ($item) => self::val($item, $key));
        }

        if (is_array($data)) {
            return (float)($data[$key] ?? 0);
        }

        if (is_object($data)) {
            return (float)($data->$key ?? 0);
        }

        return 0;
    }

    public static function calculate(Collection|array $groupedAccounts): array
    {
        if ($groupedAccounts instanceof Collection) {
            $groupedAccounts = $groupedAccounts->toArray();
        }

        /*
        |--------------------------------------------------------------------------
        | AKTIVA
        |--------------------------------------------------------------------------
        */

        $asetLancar = collect($groupedAccounts['AKTIVA']['Aset Lancar - Kas & Bank'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $persediaan = collect($groupedAccounts['AKTIVA']['Aset Lancar - Persediaan Barang'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $piutang = collect($groupedAccounts['AKTIVA']['Aset Lancar - Piutang'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $danaBelumDisetor = collect($groupedAccounts['AKTIVA']['Aset Lancar - Dana Belum Disetor'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $pajakDimuka = collect($groupedAccounts['AKTIVA']['Aset Lancar - Pajak Bayar Dimuka'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $asetTetap = collect($groupedAccounts['AKTIVA']['Aset Tetap'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $akumulasiPenyusutan = collect($groupedAccounts['AKTIVA']['Penyusutan'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $totalAktiva =
            $asetLancar
            + $persediaan
            + $piutang
            + $danaBelumDisetor
            + $pajakDimuka
            + $asetTetap
            - $akumulasiPenyusutan;

        /*
        |--------------------------------------------------------------------------
        | PASSIVA
        |--------------------------------------------------------------------------
        */

        $kewajiban = collect($groupedAccounts['KEWAJIBAN'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $ekuitas = collect($groupedAccounts['EKUITAS'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        /*
        |--------------------------------------------------------------------------
        | LABA BERJALAN
        |--------------------------------------------------------------------------
        */

        $pendapatan = collect($groupedAccounts['PENDAPATAN'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $beban = collect($groupedAccounts['BEBAN'] ?? [])
            ->sum(fn ($item) => self::val($item, 'subtotalBalance'));

        $labaBerjalan = $pendapatan - $beban;

        $totalPassiva =
            $kewajiban
            + $ekuitas
            + $labaBerjalan;

        return [

            'asetLancar' => $asetLancar,
            'persediaan' => $persediaan,
            'piutang' => $piutang,
            'dana' => $danaBelumDisetor,
            'pajak' => $pajakDimuka,
            'asetTetap' => $asetTetap,
            'penyusutan' => $akumulasiPenyusutan,

            'kewajiban' => $kewajiban,
            'ekuitas' => $ekuitas,

            'pendapatan' => $pendapatan,
            'beban' => $beban,
            'labaBerjalan' => $labaBerjalan,

            'totalAktiva' => $totalAktiva,
            'totalPassiva' => $totalPassiva,
        ];
    }
}