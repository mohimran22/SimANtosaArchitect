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

class ContractBuildController extends Controller
{
public function storeBuildTermin(Request $request, $projectId)
{
    abort_if(
        auth()->user()->cannot('ubah data proyek'),
        403
    );

    $project = Project::with([
        'offer',
        'levels',
    ])->findOrFail($projectId);

    // Pastikan hanya project Build
    if ((int) $project->project_type !== 3) {
        abort(404);
    }

    // Pastikan offer sudah ada
    if (!$project->offer) {
        return back()->withErrors([
            'termin' => 'Penawaran Build belum tersedia.'
        ]);
    }

    $validated = $request->validate([
        'percentage' => [
            'required',
            'array',
            'min:1',
        ],

        'percentage.*' => [
            'required',
            'numeric',
            'min:0.01',
            'max:100',
        ],

        'description' => [
            'nullable',
            'array',
        ],

        'description.*' => [
            'nullable',
            'string',
            'max:255',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Validasi total persentase
    |--------------------------------------------------------------------------
    */

    $totalPercentage = collect($validated['percentage'])
        ->sum(fn ($percentage) => (float) $percentage);

    if (abs($totalPercentage - 100) > 0.01) {
        return back()
            ->withErrors([
                'percentage' =>
                    'Total persentase termin harus tepat 100%.'
            ])
            ->withInput();
    }


    DB::beginTransaction();

    try {

        $offerTotal = (float) $project->offer->grand_total;


        /*
        |--------------------------------------------------------------------------
        | Hapus termin lama
        |--------------------------------------------------------------------------
        |
        | Ini penting kalau nanti Setting Termin diedit.
        |
        */

        $project->buildTermins()->delete();


        /*
        |--------------------------------------------------------------------------
        | Simpan termin baru
        |--------------------------------------------------------------------------
        */

        foreach ($validated['percentage'] as $index => $percentage) {

            $percentage = (float) $percentage;

            $amount = $offerTotal * ($percentage / 100);

            $project->buildTermins()->create([
                'termin_no' => $index + 1,

                'percentage' => $percentage,

                'amount' => $amount,

                'description' =>
                    $validated['description'][$index]
                    ?? null,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Level 5 - Setting Termin
        |--------------------------------------------------------------------------
        */

        ProjectLevel::where([
            'project_id' => $project->id,
            'level_order' => 5,
        ])->update([
            'is_completed' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Level 6 - Kontrak Kerja
        |--------------------------------------------------------------------------
        */

        ProjectLevel::where([
            'project_id' => $project->id,
            'level_order' => 6,
        ])->update([
            'is_started' => true,
        ]);


        DB::commit();


        return redirect()
            ->route('projects.create', [
                'project_id' => $project->id,
            ])
            ->with(
                'success',
                'Setting termin Build berhasil disimpan.'
            );


    } catch (\Throwable $e) {

        DB::rollBack();

        \Log::error(
            'Gagal menyimpan setting termin Build',
            [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]
        );

        return back()
            ->withErrors([
                'termin' =>
                    'Terjadi kesalahan saat menyimpan setting termin.'
            ])
            ->withInput();
    }
}
public function buildpdf(Project $project)
{
    $offer = $project->offer;

    abort_if(
        !$offer,
        404,
        'Penawaran belum tersedia'
    );

    $rab = $offer->rab;

    $items = $offer->items()->orderBy('sort_order')->get();

    Carbon::setLocale('id');

    $tanggal = Carbon::parse($offer->offer_date ?? now());

    $jobDuration = (int) ($rab->job_duration ?? 0);

    $data = [
        'project' => $project,

        'offer' => $offer,

        'rab' => $rab,
        'items' => $items,

        'customer' =>optional($project->customer->user)->fullname,

        'hari' =>$tanggal->translatedFormat('l'),

        'tanggal' => $tanggal->day,

        'tanggal_terbilang' => terbilang($tanggal->day),

        'job_duration' => $jobDuration,

        'job_duration_text' => terbilang($jobDuration),

        'bulan' => $tanggal->translatedFormat('F'),

        'tahun' => $tanggal->year,

        'tahun_terbilang' => terbilang($tanggal->year),
    ];

    $pdf = Pdf::loadView(
        'contract.buildpdf',
        $data
    )->setPaper(
        'A4',
        'portrait'
    );

    return $pdf->stream(
        'Draft-Kontrak-' .
        $project->project_name .
        '.pdf'
    );
}

        public function approve(Project $project)
    {
        abort_if(
            $project->customer->user_id !== auth()->id()
            && auth()->user()->cannot('lihat daftar proyek'),
            403
        );

        $offer = $project->offer;
            if (!$offer) {
                return back()->with('error','Offer belum dibuat.');
            }

            // if (!$offer->downloaded_at) {
            //     return back()->with('error','Kontrak belum didownload.');
            // }

            if ($offer->approved_at) {
                return back()->with('info','Kontrak sudah disetujui.');
            }

        DB::transaction(function () use ($project, $offer) {
            
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
            ->with('success', 'Kontrak disetujui. Tahap Invoice DP dimulai.');
    }

protected function generateContractNumber(): string
{
    return DB::transaction(function () {

        $now = now();
        $yearFull = $now->format('Y'); // 2026
        $yearShort = $now->format('y'); // 26
        $bulanRomawi = \App\Helpers\GeneralHelper::bulanRomawi($now->month);

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

        return "SPK/BLD/$yearShort/$bulanRomawi/$nomorUrut";
    });
}
}



