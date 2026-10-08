<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Project;
use App\Models\ProjectLevel;
use App\Models\ContractCounter;
use App\Services\ProjectNotifier;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function pdf(Project $project)
    {
        $offer = $project->offer;

        abort_if(!$offer, 404, 'Penawaran belum tersedia');
        
        Carbon::setLocale('id');

        $tanggal = Carbon::parse($offer->contract_date ?? now());
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

        return $pdf->stream('Kontrak-' . $project->project_name . '.pdf');
    }

    public function updateDate(Request $request, Project $project)
    {
        abort_if(auth()->user()->cannot('ubah data proyek'), 403);

        abort_if(
            !in_array((int) $project->project_type, [1, 3], true),
            404,
            'Jenis proyek ini tidak memiliki kontrak'
        );

        $offer = $project->offer;

        abort_if(!$offer, 404, 'Penawaran belum tersedia');

        $validated = $request->validate([
            'contract_date' => ['required', 'date'],
        ]);

        $date   = Carbon::parse($validated['contract_date']);
        $prefix = (int) $project->project_type === 3 ? 'BLD' : 'DSN';

        // Setelah kontrak dilanjutkan (approved_at terisi), nomor dibekukan
        // karena mungkin sudah dipakai di dokumen lain; hanya tanggal yang berubah.
        $numberLocked = (bool) $offer->approved_at;

        DB::transaction(function () use ($offer, $date, $prefix, $numberLocked) {
            $offer->update([
                'contract_number' => $numberLocked
                    ? $offer->contract_number
                    : $this->resolveContractNumber($offer->contract_number, $date, $prefix),
                'contract_date'   => $date,
            ]);
        });

        return back()->with('success', 'Tanggal kontrak disimpan.');
    }

    public function next(Project $project)
    {
        abort_if(
            $project->customer->user_id !== auth()->id()
            && auth()->user()->cannot('lihat daftar proyek'),
            403
        );

        $offer = $project->offer;

        if (!$offer) {
            return back()->with('error', 'Offer belum dibuat.');
        }

        if ($offer->approved_at) {
            return back()->with('info', 'Tahap kontrak sudah dilanjutkan.');
        }

        if (!$offer->contract_date || !$offer->contract_number) {
            return back()->with('error', 'Simpan tanggal kontrak terlebih dahulu.');
        }

        DB::transaction(function () use ($project, $offer) {
            // approved_at/approved_by tetap diisi sebagai penanda kontrak sudah final,
            // karena bagian lain (invoice, form pengerjaan) masih membacanya.
            $offer->update([
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            ProjectLevel::complete($project->id, 5);

            ProjectLevel::where([
                'project_id'  => $project->id,
                'level_order' => 6,
            ])->update([
                'is_started' => true,
            ]);
        });

        $event = 'contract_created';
        $cfg   = config("project_events.contract_created");

        if (!$cfg) {
            throw new \Exception("Config project_events.$event not found");
        }

        ProjectNotifier::notifyUsers(
            [$project->createdBy ?? auth()->user()],
            ProjectNotifier::makePayload($project, [
                'type'    => $event,
                'role'    => 'Super-Admin',
                'title'   => $cfg['title'],
                'message' => $cfg['message']['Super-Admin'],
                'url'     => route('projects.create', ['project_id' => $project->id]),
            ])
        );

        if ($project->customer?->user) {
            ProjectNotifier::notifyUsers(
                [$project->customer->user],
                ProjectNotifier::makePayload($project, [
                    'type'    => $event,
                    'role'    => 'Customer',
                    'title'   => $cfg['title'],
                    'message' => $cfg['message']['customer'],
                    'url'     => route('projects.create', ['project_id' => $project->id]),
                ])
            );
        }

        return redirect()
            ->route('projects.create', ['project_id' => $project->id])
            ->with('success', 'Kontrak disimpan. Tahap Invoice DP dimulai.');
    }
/**
 * Nomor kontrak dibuat saat tanggal pertama kali disimpan.
 * Jika tanggal diubah kemudian: tahun sama -> hanya bulan romawi yang disesuaikan
 * (nomor urut tetap); tahun berbeda -> ambil nomor baru dari counter tahun tersebut.
 */
protected function resolveContractNumber(?string $current, Carbon $date, string $prefix): string
{
    if ($current) {
        $parts = explode('/', $current); // SPK/DSN/26/IX/048

        if (count($parts) === 5 && $parts[2] === $date->format('y')) {
            $parts[3] = GeneralHelper::bulanRomawi($date->month);

            return implode('/', $parts);
        }
    }

    return $this->generateContractNumber($date, $prefix);
}

protected function generateContractNumber(Carbon $date, string $prefix): string
{
    return DB::transaction(function () use ($date, $prefix) {

        $yearFull = $date->format('Y'); // 2026
        $yearShort = $date->format('y'); // 26
        $bulanRomawi = GeneralHelper::bulanRomawi($date->month);

        $counter = ContractCounter::where('year', $yearFull)
            ->lockForUpdate()
            ->first();

        if (!$counter) {
            $counter = ContractCounter::create([
                'year' => $yearFull,
                'last_number' => 0,
            ]);
        }

        $next = $counter->last_number + 1;

        $counter->update([
            'last_number' => $next,
        ]);

        $nomorUrut = str_pad($next, 3, '0', STR_PAD_LEFT);

        return "SPK/$prefix/$yearShort/$bulanRomawi/$nomorUrut";
    });
}
}