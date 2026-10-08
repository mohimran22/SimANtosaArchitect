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

class ContractBuildController extends Controller
{

public function buildpdf(Project $project)
{
    $offer = $project->offer;

    abort_if(
        !$offer,
        404,
        'Penawaran belum tersedia'
    );

    $rab = $offer->rab;

    $items = $offer->items()
        ->orderBy('sort_order')
        ->get()
        ->groupBy('floor_name');

    $termins = $project->buildTermins()
        ->orderBy('termin_no')
        ->get();

    abort_if(
        $termins->isEmpty(),
        404,
        'Setting termin Build belum tersedia'
    );

    Carbon::setLocale('id');

    $tanggal = Carbon::parse(
        $offer->contract_date ?? now()
    );

    $jobDuration = (int) ($rab->job_duration ?? 0);

    $data = [
        'project' => $project,

        'offer' => $offer,

        'rab' => $rab,
        'items' => $items,
        'termins' => $termins,

        'customer' => optional(
            $project->customer->user
        )->fullname,

        'hari' => $tanggal->translatedFormat('l'),

        'tanggal' => $tanggal->day,

        'tanggal_terbilang' => terbilang(
            $tanggal->day
        ),

        'job_duration' => $jobDuration,

        'job_duration_text' => terbilang(
            $jobDuration
        ),

        'bulan' => $tanggal->translatedFormat('F'),

        'tahun' => $tanggal->year,

        'tahun_terbilang' => terbilang(
            $tanggal->year
        ),
    ];

    $pdf = Pdf::loadView(
        'contract.buildpdf',
        $data
    )->setPaper(
        'A4',
        'portrait'
    );

    return $pdf->stream(
        'Kontrak-' .
        $project->project_name .
        '.pdf'
    );
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

            ProjectLevel::complete($project->id, 6);

            ProjectLevel::where([
                'project_id'  => $project->id,
                'level_order' => 7,
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
            ->with('success', 'Kontrak disimpan. Tahap Invoice Termin dimulai.');
    }
}