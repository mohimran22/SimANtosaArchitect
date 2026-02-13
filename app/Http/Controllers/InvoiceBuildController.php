<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\InvoiceBuild;
use App\Models\ProjectLevel;
use App\Models\BuildProcessItem;
use App\Models\BuildProcessProgress;
use App\Services\ProjectNotifier;
use App\Services\InvoiceBuildNumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;

class InvoiceBuildController extends Controller
{

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
                    'invoice_type'        => InvoiceBuild::TYPE_BUILD,
                    'invoice_number'      => InvoiceBuildNumberGenerator::generate($termin),
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

    public function approve(Project $project, InvoiceBuild $invoice)
    {
        abort_if($project->project_type != 3, 403);
        abort_if($invoice->project_id !== $project->id, 404);

        abort_if(
            $project->customer->user_id !== auth()->id()
            && auth()->user()->cannot('lihat daftar proyek'),
            403
        );

        if (!$invoice->downloaded_at) {
            return back()->with('error','Invoice belum didownload.');
        }

        if ($invoice->approved_at) {
            return back()->with('info','Invoice sudah disetujui.');
        }

        DB::transaction(function () use ($invoice, $project) {

            $invoice->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approve_by_name' => auth()->user()->fullname ?? 'Customer',
                'approved_ip' => request()->ip(),
            ]);

            if ($project->buildItems()->count() == 0) {

                $project->load('offer.rab.items.category');

                foreach ($project->offer->rab->items as $item) {
                    BuildProcessItem::create([
                        'project_id' => $project->id,
                        'rab_item_id' => $item->id,
                        'job_category_id' => $item->job_category_id,
                        'price' => $item->price,
                        'uraian' => $item->job_name,
                        'volume' => $item->volume,
                        'total' => $item->total,
                        'satuan' => $item->satuan ?? null,
                        'bobot_percent' => 0,
                    ]);
                }
            }

            ProjectLevel::where([
                'project_id' => $project->id,
                'level_order' => 6,
            ])->update(['is_completed' => true]);

            ProjectLevel::where([
                'project_id' => $project->id,
                'level_order' => 7,
            ])->update(['is_started' => true]);

            $project->update([
                'active_step' => 7
            ]);
        });

        $event = 'invoice_build_created';
        $cfg   = config("project_events.$event");

        $payloadExtra = [
            'termin'          => $invoice->termin,
            'amount'          => number_format($invoice->amount, 0, ',', '.'),
            'progress_start'  => $invoice->progress_start,
            'progress_end'    => $invoice->progress_end,
        ];

        if (!$cfg) {
            throw new \Exception("Config project_events.$event not found");
        }

        ProjectNotifier::notifyUsers(
            [$project->createdBy ?? auth()->user()],
            ProjectNotifier::makePayload($project, [
                'type'    => $event,
                'role'    => 'Super-Admin',
                'title'   => ProjectNotifier::parseMessage($cfg['title'], $payloadExtra),
                'message' => ProjectNotifier::parseMessage(
                    $cfg['message']['Super-Admin'],
                    $payloadExtra
                ),
                'url'     => route('projects.create', ['project_id' => $project->id]),
            ])
        );

        if ($project->customer?->user) {
            ProjectNotifier::notifyUsers(
                [$project->customer->user],
                ProjectNotifier::makePayload($project, [
                    'type'    => $event,
                    'role'    => 'Customer',
                    'title'   => ProjectNotifier::parseMessage($cfg['title'], $payloadExtra),
                    'message' => ProjectNotifier::parseMessage(
                        $cfg['message']['customer'],
                        $payloadExtra
                    ),
                    'url'     => route('projects.create', ['project_id' => $project->id]),
                ])
            );
        }


        return redirect()
            ->route('projects.create', ['project_id' => $project->id])
            ->with(
                'success',
                "Invoice Termin {$invoice->termin} berhasil disetujui."
            );
    }
}
