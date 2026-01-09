<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Project;
use App\Models\ProjectLevel;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ContractController extends Controller
{
    public function pdf(Project $project)
    {
        $offer = $project->offer;

        abort_if(!$offer, 404, 'Penawaran belum tersedia');
        
        Carbon::setLocale('id');

        $tanggal = Carbon::parse($offer->offer_date ?? now());
        $items = $offer->package
            ->items
            ->where('is_optional', false)
            ->groupBy('category');

        $data = [
            'project'  => $project,
            'offer'    => $offer,
            'customer' => optional($project->customer->user)->fullname,
            'designItems' => $items,
            'hari'              => $tanggal->translatedFormat('l'),
            'tanggal'           => $tanggal->day,
            'tanggal_terbilang' => terbilang($tanggal->day),
            'bulan'             => $tanggal->translatedFormat('F'),
            'tahun'             => $tanggal->year,
            'tahun_terbilang'   => terbilang($tanggal->year),
        ];

        $pdf = Pdf::loadView('contract.pdf', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Draft-Kontrak-' . $project->project_name . '.pdf');
    }

        public function approve(Project $project)
    {
        DB::transaction(function () use ($project) {
            $offer = $project->offer;

            if (!$offer->contract_number) {
                $offer->update([
                    'contract_number' => $this->generateContractNumber(),
                    'contract_date'   => now(),
                    'approved_at'   => now(),
                    'approved_by'   => auth()->id(),
                ]);
            }

            ProjectLevel::where([
                'project_id'  => $project->id,
                'level_order' => 5,
            ])->update([
                'is_completed' => true,
            ]);

            ProjectLevel::where([
                'project_id'  => $project->id,
                'level_order' => 6,
            ])->update([
                'is_started' => true,
            ]);
        });

        return redirect()
            ->route('projects.create', ['project_id' => $project->id])
            ->with('success', 'Kontrak disetujui. Tahap Invoice DP dimulai.');
    }

protected function generateContractNumber()
{
    $tahunFull = date('Y');   // 2026
    $tahun = date('y');       // 26
    $bulan = date('n');       // 1-12
    $romawiBulan = \App\Helpers\GeneralHelper::bulanRomawi($bulan);

    // Ambil nomor terakhir di tahun ini
    $last = \App\Models\Offer::whereYear('contract_date', $tahunFull)
        ->whereNotNull('contract_number')
        ->orderByDesc('id')
        ->first();

    if ($last) {
        // SPK/DSN/26/I/001 → ambil 001
        $explode = explode('/', $last->contract_number);
        $lastNumber = (int) end($explode) + 1;
    } else {
        $lastNumber = 1;
    }

    // Format 3 digit: 1 → 001
    $nomorUrut = str_pad($lastNumber, 3, '0', STR_PAD_LEFT);

    return "SPK/DSN/$tahun/$romawiBulan/$nomorUrut";
}


}
