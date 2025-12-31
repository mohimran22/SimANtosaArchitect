<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\GeneralHelper;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\ProjectLevel;
use App\Models\ProjectTask;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function invoiceDp(Project $project)
    {
        abort_if(!$project->offer, 404);

        Carbon::setLocale('id');

        $invoice = Invoice::firstOrCreate(
            [
                'project_id'   => $project->id,
                'invoice_type' => Invoice::TYPE_DP,
            ],
            [
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date'   => now(),
                'amount'         => $project->offer->grand_total * 0.7,
                'status'         => Invoice::STATUS_WAITING,
            ]
        );

        if (!$invoice->invoice_dp_downloaded_at) {
            $invoice->update([
                'invoice_dp_downloaded_at' => now(),
            ]);
        }

        $offer = $project->offer;

        $data = [
            'invoice_number'   => $invoice->invoice_number,
            'invoice_date'     => $invoice->invoice_date->translatedFormat('d F Y'),
            'client_name'      => $offer->contact_name,
            'client_address'   => optional($project->customer->user)->address,
            'client_phone'     => optional($project->customer->user)->phone,
            'project_name'     => $project->project_name,
            'grand_total'      => $offer->grand_total,
            'dp_amount'        => $offer->grand_total * 0.7,
            'remaining_amount' => $offer->grand_total * 0.3,
        ];

        return Pdf::loadView('invoice.dp', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Invoice-DP-' . $project->project_name . '.pdf');
    }

public function invoiceFinal(Project $project)
{
    abort_if(!$project->offer, 404);

    Carbon::setLocale('id');

    $invoice = Invoice::firstOrCreate(
        [
            'project_id'   => $project->id,
            'invoice_type' => Invoice::TYPE_FINAL,
        ],
        [
            'invoice_number' => $this->generateInvoiceNumber(),
            'invoice_date'   => now(),
            'amount'         => $project->offer->grand_total * 0.3,
            'status'         => Invoice::STATUS_WAITING,
        ]
    );

    if (!$invoice->downloaded_at) {
        $invoice->update([
            'downloaded_at' => now(),
        ]);
    }

    $offer = $project->offer;

    $data = [
        'invoice_number'   => $invoice->invoice_number,
        'invoice_date'     => $invoice->invoice_date->translatedFormat('d F Y'),
        'client_name'      => $offer->contact_name,
        'client_address'   => optional($project->customer->user)->address,
        'client_phone'     => optional($project->customer->user)->phone,
        'project_name'     => $project->project_name,
        'grand_total'      => $offer->grand_total,
        'dp_amount'        => $offer->grand_total * 0.7,
        'final_amount'     => $offer->grand_total * 0.3,
    ];

    return Pdf::loadView('invoice.final', $data)
        ->setPaper('A4', 'portrait')
        ->stream('Invoice-Pelunasan-' . $project->project_name . '.pdf');
}

    public function invoiceSurvey(Project $project)
{
    abort_if(!$project->planning, 404);

    Carbon::setLocale('id');

    $invoice = Invoice::where('project_id', $project->id)
        ->where('invoice_type', 'survey')
        ->firstOrFail();

    if (!$invoice->printed_at) {
        $invoice->update([
            'printed_at' => now(),
        ]);
    }

    $planning = $project->planning;

    $data = [
        'invoice' => $invoice,
        'project' => $project,
        'planning' => $planning,
    ];

    return Pdf::loadView('invoice.survey', $data)
        ->setPaper('A4', 'portrait')
        ->stream('Invoice-Rencana-Survei-' . $project->project_name . '.pdf');
}

public function invoiceRab(Project $project)
{
    abort_if(!$project->offer, 404);
    abort_if($project->project_type != 2, 403); // 🔒 khusus RAB

    Carbon::setLocale('id');

    $invoice = Invoice::firstOrCreate(
        [
            'project_id'   => $project->id,
            'invoice_type' => Invoice::TYPE_RAB,
        ],
        [
            'invoice_number' => $this->generateInvoiceNumber(),
            'invoice_date'   => now(),
            'amount'         => $project->offer->grand_total,
            'status'         => Invoice::STATUS_WAITING,
        ]
    );

    if (!$invoice->downloaded_at) {
        $invoice->update([
            'downloaded_at' => now(),
        ]);
    }

    $offer = $project->offer;

    $data = [
        'invoice_number' => $invoice->invoice_number,
        'invoice_date'   => $invoice->invoice_date->translatedFormat('d F Y'),
        'client_name'    => $offer->contact_name,
        'client_address' => optional($project->customer->user)->address,
        'client_phone'   => optional($project->customer->user)->phone,
        'project_name'   => $project->project_name,
        'total_amount'   => $offer->grand_total,
    ];

    return Pdf::loadView('invoice.rab', $data)
        ->setPaper('A4', 'portrait')
        ->stream('Invoice-RAB-' . $project->project_name . '.pdf');
}

    public function approve(Project $project)
    {
        DB::transaction(function () use ($project) {

        $invoice = Invoice::where('project_id', $project->id)
            ->where('invoice_type', Invoice::TYPE_DP)
            ->firstOrFail();

            abort_if(!$invoice, 404, 'Invoice DP belum dibuat.');

            $invoice->update([
                'invoice_dp_approved_at' => now(),
                'status' => 'dp',
                'approved_at' => now(),
            ]);

            $offer = $project->offer;
            abort_if(!$offer, 404, 'Offer belum tersedia.');

            if ($project->tasks()->count() === 0) {
                foreach ($offer->items as $item) {
                    ProjectTask::create([
                        'project_id' => $project->id,
                        'offer_id'   => $offer->id,
                        'category'   => $item->category,
                        'task_name'  => $item->item_name,
                    ]);
                }
            }
 
            ProjectLevel::where([
                'project_id'  => $project->id,
                'level_order' => 6,
            ])->update(['is_completed' => true]);

            // LEVEL 7 DIMULAI
            ProjectLevel::where([
                'project_id'  => $project->id,
                'level_order' => 7,
            ])->update(['is_started' => true]);
        });

        return redirect()
            ->route('projects.create', ['project_id' => $project->id])
            ->with('success', 'Invoice DP selesai, lanjut ke tahap pengerjaan.');
    }

    public function approveFinal(Project $project)
{
    DB::transaction(function () use ($project) {

        $invoice = Invoice::where('project_id', $project->id)
            ->where('invoice_type', Invoice::TYPE_FINAL)
            ->firstOrFail();

        $invoice->update([
            'status'      => Invoice::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        // Level pengerjaan selesai
        ProjectLevel::where([
            'project_id'  => $project->id,
            'level_order' => 8,
        ])->update(['is_completed' => true]);

        // Level selesai proyek
        ProjectLevel::where([
            'project_id'  => $project->id,
            'level_order' => 9,
        ])->update(['is_started' => true]);
    });

    return redirect()
        ->route('projects.create', ['project_id' => $project->id])
        ->with('success', 'Pelunasan disetujui. Proyek selesai.');
}

    public function approveRab(Project $project)
{
    DB::transaction(function () use ($project) {

        $invoice = Invoice::where('project_id', $project->id)
            ->where('invoice_type', Invoice::TYPE_RAB)
            ->firstOrFail();

        $invoice->update([
            'status'      => Invoice::STATUS_PAID,
            'approved_at' => now(),
        ]);

        // Level pengerjaan selesai
        ProjectLevel::where([
            'project_id'  => $project->id,
            'level_order' => 5,
        ])->update(['is_completed' => true]);

        // Level selesai proyek
        ProjectLevel::where([
            'project_id'  => $project->id,
            'level_order' => 6,
        ])->update(['is_started' => true]);
    });

    return redirect()
        ->route('projects.create', ['project_id' => $project->id])
        ->with('success', 'RAB disetujui. Lanjut ke tahap pengerjaan.');
}


    protected function generateInvoiceNumber()
    {
        $now = now();

        $last = Invoice::whereYear('invoice_date', $now->year)
            ->whereMonth('invoice_date', $now->month)
            ->orderBy('invoice_date', 'desc')
            ->first();

        $urut = 1;

        if ($last && preg_match('/(\d{3})$/', $last->invoice_number, $m)) {
            $urut = (int) $m[1] + 1;
        }

        return sprintf(
            'INV/%s/%s/%03d',
            $now->year,
            $now->format('m'),
            $urut
        );
    }

public function surveyPlanningPdf(Project $project)
{
    $invoice = Invoice::with([
        'project.customer.user',
        'project.levels.employees',
        'project.planning.province',
        'project.planning.city',
        'project.planning.district',
        'project.planning.subDistrict',
        'project.planning.postalCode',
    ])
    ->where('project_id', $project->id)
    ->where('invoice_type', 'survey')
    ->where('amount', '>', 0)
    ->latest()
    ->firstOrFail();

    $planningEmployees = $project->levels()
    ->where('level_order', 2)
    ->with('employees')
    ->get()
    ->flatMap->employees;


    return Pdf::loadView('pdf.planning-survey', [
        'invoice'  => $invoice,
        'project'  => $project,
        'planning' => $project->planning,
        'planningEmployees'  => $planningEmployees,
    ])->stream('rencana-survei.pdf');
}
public function approveSurvey(Invoice $invoice, $token)
{
    abort_if(
        $invoice->approval_token !== $token ||
        $invoice->invoice_type !== 'survey',
        403
    );

    if ($invoice->status === 'approved') {
        return view('survey.approval-result', [
            'status' => 'approved',
            'message' => 'Rencana survei sudah disetujui sebelumnya.'
        ]);
    }

    $invoice->update([
        'status' => 'approved',
        'approved_at' => now(),
    ]);

    $project = $invoice->project;

    $planningLevel = $project->levels()
        ->where('level_name', 'Rencana Survei')
        ->first();

    if ($planningLevel && !$planningLevel->is_completed) {
        $planningLevel->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    $surveyLevel = $project->levels()
        ->where('level_name', 'Survei')
        ->first();

    if ($surveyLevel && !$surveyLevel->is_started) {
        $surveyLevel->update([
            'is_started' => true,
            'started_at' => now(),
        ]);
    }

    return view('survey.approval-result', [
        'status' => 'approved',
        'message' => 'Rencana survei berhasil disetujui. Tim survei dapat mulai bekerja.'
    ]);
}
public function rejectSurveyForm(Invoice $invoice, $token)
{
    abort_if(
        $invoice->approval_token !== $token ||
        $invoice->invoice_type !== 'survey',
        403
    );

    return view('survey.reject-form', compact('invoice', 'token'));
}
public function rejectSurvey(Request $request, Invoice $invoice, $token)
{
    abort_if(
        $invoice->approval_token !== $token ||
        $invoice->invoice_type !== 'survey',
        403
    );

    $request->validate([
        'reject_note' => 'required|min:5'
    ]);

    $invoice->update([
        'status'       => 'rejected',
        'reject_note'  => $request->reject_note,
        'approved_at'  => null,
        'rejected_at'  => now(),
    ]);

    $project = $invoice->project;

    if ($project) {

        $surveyLevel = $project->levels()
            ->where('level_name', 'Survei')
            ->first();

        if ($surveyLevel) {
            $surveyLevel->update([
                'is_started'   => false,
                'is_completed' => false,
                'started_at'   => null,
                'completed_at' => null,
            ]);
        }

        $planningLevel = $project->levels()
            ->where('level_name', 'Rencana Survei')
            ->first();

        if ($planningLevel) {
            $planningLevel->update([
                'is_started'   => true,
                'is_completed' => false,
            ]);
        }
    }

    return redirect()
        ->route('projects.create', ['project_id' => $project->id])
        ->with('error', 'Rencana survei ditolak oleh customer. Silakan perbaiki data.');
}
}