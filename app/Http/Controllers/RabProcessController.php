<?php

namespace App\Http\Controllers;

use App\Models\RabProcess;
use App\Models\RabProcessItem;
use App\Models\JobCategory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;

class RabProcessController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'project_id' => 'required|exists:projects,id',
        'contact_name' => 'required|string',
        'job_location' => 'required|string',
        'job_duration' => 'nullable|string',

        'items' => 'required|array|min:1',
        'items.*.job_category_id' => 'required|exists:job_categories,id',
        'items.*.job_name' => 'required|string',
        'items.*.satuan' => 'required|string',
        'items.*.volume' => 'required|numeric|min:0.01',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.profit' => 'required|numeric|max:100',
        'items.*.overhead' => 'required|numeric|max:100',
        'items.*.total' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'tax_rate' => 'nullable|numeric|min:0|max:100',
        'shipping' => 'nullable|numeric|min:0',

        'notes' => 'nullable|string',
    ]);

    DB::transaction(function () use ($request) {

        $project = Project::findOrFail($request->project_id);

        $subtotal = collect($request->items)->sum(function ($item) {
            return (float) $item['total'];
        });

        $discount        = (float) ($request->discount ?? 0);
        $taxRate         = (float) ($request->tax_rate ?? 0);
        $shipping        = (float) ($request->shipping ?? 0);

        $base = $subtotal;

        $subtotalAfterDiscount = max($base - $discount, 0);

        $taxTotal = $subtotalAfterDiscount * ($taxRate / 100);

        $grandTotal = $subtotalAfterDiscount + $taxTotal + $shipping;

        $rab = RabProcess::create([
            'project_id' => $project->id,
            'contact_name' => $request->contact_name,
            'job_location' => $request->job_location,
            'job_duration' => $request->job_duration,

            'subtotal' => $subtotal,
            'discount' => $discount,
            'subtotal_after_discount' => $subtotalAfterDiscount,

            'tax_rate' => $taxRate,
            'tax_total' => $taxTotal,

            'shipping' => $shipping,
            'grand_total' => $grandTotal,

            'notes' => $request->notes,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'analisa_version' => Cache::get('job_category_last_updated', 0),
        ]);

        foreach ($request->items as $item) {
            $base = $item['volume'] * $item['price'];

            $profitValue = $base * (($item['profit'] ?? 0) / 100);
            $overheadValue = $base * (($item['overhead'] ?? 0) / 100);

            $total = $base + $profitValue + $overheadValue;
            RabProcessItem::create([
                'rab_process_id' => $rab->id,
                'job_category_id' => $item['job_category_id'],
                'job_name' => $item['job_name'],
                'satuan' => $item['satuan'],
                'volume' => $item['volume'],
                'profit' => $item['profit'],
                'overhead' => $item['overhead'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
        }

        $finalLevel = $project->levels()
            ->where('level_name', 'Proses Pengerjaan RAB')
            ->first();

        if ($finalLevel && !$finalLevel->is_completed) {
            $finalLevel->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);
        }
    });

    return back()->with('success', 'RAB berhasil disimpan dan proyek dinyatakan selesai');
}

public function exportPdf(Project $project)
{
    $rab = $project->rab;
    if (!$rab) abort(404);

    $grouped = [];

    foreach ($rab->items as $item) {

        $kode = $item->category->kode_group ?? '-';
        $nama = $item->category->nama_group ?? 'PEKERJAAN LAIN-LAIN';

        if (!isset($grouped[$kode])) {
            $grouped[$kode] = [
                'kode' => $kode,
                'nama' => $nama,
                'items' => [],
                'subtotal' => 0
            ];
        }

        $grouped[$kode]['items'][] = $item;
        $grouped[$kode]['subtotal'] += $item->total;
    }

    $pdf = Pdf::loadView('rab.pdf', compact('rab', 'project', 'grouped'))
        ->setPaper('A4', 'portrait');

    return $pdf->stream('RAB-'.$project->name.'.pdf');
}

public function update(Request $request, Project $project, RabProcess $rab)
{
    $request->validate([
        'contact_name' => 'required|string',
        'job_location' => 'required|string',
        'job_duration' => 'nullable|string',

        'items' => 'required|array|min:1',
        'items.*.job_category_id' => 'required|exists:job_categories,id',
        'items.*.job_name' => 'required|string',
        'items.*.satuan' => 'required|string',
        'items.*.volume' => 'required|numeric|min:0.01',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.profit' => 'required|numeric|max:100',
        'items.*.overhead' => 'required|numeric|max:100',
        'items.*.total' => 'required|numeric|min:0',

        'discount' => 'nullable|numeric|min:0',
        'tax_rate' => 'nullable|numeric|min:0|max:100',
        'shipping' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
    ]);

    DB::transaction(function () use ($request, $rab, $project) {

        // ================= HITUNG ULANG SUMMARY =================
        $subtotal = collect($request->items)->sum(fn ($item) => (float) $item['total']);

        $discount = (float) ($request->discount ?? 0);
        $taxRate  = (float) ($request->tax_rate ?? 0);
        $shipping = (float) ($request->shipping ?? 0);

        $subtotalAfterDiscount = max($subtotal - $discount, 0);
        $taxTotal = $subtotalAfterDiscount * ($taxRate / 100);
        $grandTotal = $subtotalAfterDiscount + $taxTotal + $shipping;

        // ================= UPDATE RAB HEADER =================
        $rab->update([
            'contact_name' => $request->contact_name,
            'job_location' => $request->job_location,
            'job_duration' => $request->job_duration,

            'subtotal' => $subtotal,
            'discount' => $discount,
            'subtotal_after_discount' => $subtotalAfterDiscount,

            'tax_rate' => $taxRate,
            'tax_total' => $taxTotal,

            'shipping' => $shipping,
            'grand_total' => $grandTotal,

            'notes' => $request->notes,
            'updated_by' => auth()->id(),
        ]);

        // ================= DELETE ITEM LAMA =================
        $rab->items()->delete();

        // ================= INSERT ITEM BARU =================
        foreach ($request->items as $item) {

            $base = $item['volume'] * $item['price'];
            $profitValue = $base * ($item['profit'] / 100);
            $overheadValue = $base * ($item['overhead'] / 100);
            $total = $base + $profitValue + $overheadValue;

            $rab->items()->create([
                'job_category_id' => $item['job_category_id'],
                'job_name' => $item['job_name'],
                'satuan' => $item['satuan'],
                'volume' => $item['volume'],
                'profit' => $item['profit'],
                'overhead' => $item['overhead'],
                'price' => $item['price'],
                'total' => $total,
            ]);
        }
    });

    return back()->with('success', 'RAB berhasil diperbarui');
}

public function refreshFromMaster(RabProcess $rab)
{
    DB::transaction(function () use ($rab) {

        $subtotal = 0;

        foreach ($rab->items as $item) {

            // Ambil harga terbaru dari job_category
            $job = JobCategory::find($item->job_category_id);

            if (!$job) continue;

            $newPrice = $job->grand_total;

            $base = $item->volume * $newPrice;

            $profitValue = $base * ($item->profit / 100);
            $overheadValue = $base * ($item->overhead / 100);

            $total = $base + $profitValue + $overheadValue;

            $item->update([
                'price' => $newPrice,
                'total' => $total,
            ]);

            $subtotal += $total;

        }

        // Hitung ulang RAB header
        $discount = $rab->discount;
        $taxRate  = $rab->tax_rate;
        $shipping = $rab->shipping;

        $afterDiscount = max($subtotal - $discount, 0);
        $taxTotal = $afterDiscount * ($taxRate / 100);
        $grandTotal = $afterDiscount + $taxTotal + $shipping;

        $rab->update([
            'subtotal' => $subtotal,
            'subtotal_after_discount' => $afterDiscount,
            'tax_total' => $taxTotal,
            'grand_total' => $grandTotal,
            'analisa_version' => Cache::get('job_category_last_updated', 0),
            'updated_by' => auth()->id(),
        ]);
    });

    return response()->json(['success' => true]);
}

}