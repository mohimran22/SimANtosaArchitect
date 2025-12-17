<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Project;
use App\Models\ProjectLevel;
use App\Models\ProjectTask;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function invoiceDp(Project $project)
    {
        abort_if(!$project->offer, 404);

        Carbon::setLocale('id');

        // BUAT / AMBIL INVOICE
        $invoice = Invoice::firstOrCreate(
            ['project_id' => $project->id],
            [
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date'   => now(),
                // 'created_by'     => auth()->id(),
            ]
        );

        // TANDAI SUDAH DOWNLOAD
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

    public function approve(Project $project)
    {
        DB::transaction(function () use ($project) {

            $invoice = Invoice::where('project_id', $project->id)->first();
            abort_if(!$invoice, 404, 'Invoice DP belum dibuat.');

            $invoice->update([
                'invoice_dp_approved_at' => now(),
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
            // LEVEL 6 SELESAI
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
}

