<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\InvoiceBuild;
use App\Services\InvoiceBuildNumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;

class InvoiceBuildController extends Controller
{
    /**
     * Generate & Download Invoice Build per Termin
     */
    public function invoiceBuild(Project $project, int $termin)
    {
        abort_if($project->project_type != 3, 403);
        abort_if(!$project->offer, 404);

        Carbon::setLocale('id');

        // Mapping termin
        $terminMap = [
            1 => ['start' => 0,  'end' => 30,  'percent' => 30],
            2 => ['start' => 30, 'end' => 60,  'percent' => 30],
            3 => ['start' => 60, 'end' => 90,  'percent' => 30],
            4 => ['start' => 90, 'end' => 100, 'percent' => 10],
        ];

        abort_if(!isset($terminMap[$termin]), 404);

        $conf = $terminMap[$termin];

        $invoice = DB::transaction(function () use ($project, $termin, $conf) {

            $invoice = InvoiceBuild::where('project_id', $project->id)
                ->where('termin', $termin)
                ->lockForUpdate()
                ->first();

            if (!$invoice) {
                $invoice = InvoiceBuild::create([
                    'project_id'          => $project->id,
                    'invoice_type'        => InvoiceBuild::TYPE_DP,
                    'invoice_number'      => InvoiceBuildNumberGenerator::generate(InvoiceBuild::TYPE_DP),
                    'invoice_date'        => now(),
                    'termin'              => $termin,
                    'progress_start'      => $conf['start'],
                    'progress_end'        => $conf['end'],
                    'payment_percentage'  => $conf['percent'],
                    'amount'              => $project->offer->grand_total * ($conf['percent'] / 100),
                    'status'              => 'waiting',
                ]);
            }

            if (!$invoice->downloaded_at) {
                $invoice->update([
                    'downloaded_at' => now(),
                ]);
            }

            return $invoice;
        });

        return Pdf::loadView('invoice.build', [
            'invoice' => $invoice,
            'project' => $project,
            'offer'   => $project->offer,
        ])
        ->setPaper('A4', 'portrait')
        ->stream(
            "Invoice-Build-Termin-{$termin}-{$project->project_name}.pdf"
        );
    }

    /**
     * Approve Invoice Build
     */
    public function approve(Project $project, InvoiceBuild $invoice)
    {
        abort_if($project->project_type != 3, 403);

        abort_if(
            $project->customer->user_id !== auth()->id()
            && auth()->user()->cannot('lihat daftar proyek'),
            403
        );

        DB::transaction(function () use ($invoice, $project) {

            $invoice->update([
                'status'      => 'approved',
                'approved_at' => now(),
                'approve_by_name' => auth()->user()->fullname ?? 'Customer',
                'approved_ip' => request()->ip(),
            ]);

            /**
             * 🔥 Logic step / level project
             * Contoh:
             * termin 1 → level 7 selesai
             * termin 2 → level 8 selesai
             * dst (sesuaikan struktur level kamu)
             */
        });

        return redirect()
            ->route('projects.create', ['project_id' => $project->id])
            ->with(
                'success',
                "Invoice Termin {$invoice->termin} berhasil disetujui."
            );
    }
}
