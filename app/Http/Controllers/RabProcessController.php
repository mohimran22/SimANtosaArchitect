<?php

namespace App\Http\Controllers;

use App\Models\RabProcess;
use App\Models\RabProcessItem;
use App\Models\JobCategory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        'items.*.total' => 'required|numeric|min:0',

        'subtotal' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'subtotal_after_discount' => 'required|numeric|min:0',
        'tax_rate' => 'nullable|numeric|min:0|max:100',
        'tax_total' => 'required|numeric|min:0',
        'shipping' => 'nullable|numeric|min:0',
        'grand_total' => 'required|numeric|min:0',

        'notes' => 'nullable|string',
    ]);

    DB::transaction(function () use ($request) {

        // 🔹 Ambil project
        $project = Project::findOrFail($request->project_id);

        // 1️⃣ SIMPAN RAB
        $rab = RabProcess::create([
            'project_id' => $project->id,
            'contact_name' => $request->contact_name,
            'job_location' => $request->job_location,
            'job_duration' => $request->job_duration,

            'subtotal' => $request->subtotal,
            'discount' => $request->discount ?? 0,
            'subtotal_after_discount' => $request->subtotal_after_discount,

            'tax_rate' => $request->tax_rate ?? 0,
            'tax_total' => $request->tax_total,

            'shipping' => $request->shipping ?? 0,
            'grand_total' => $request->grand_total,

            'notes' => $request->notes,
        ]);

        // 2️⃣ SIMPAN ITEMS
        foreach ($request->items as $item) {
            RabProcessItem::create([
                'rab_process_id' => $rab->id,
                'job_category_id' => $item['job_category_id'],
                'job_name' => $item['job_name'],
                'satuan' => $item['satuan'],
                'volume' => $item['volume'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
        }

        // 3️⃣ TANDAI LEVEL TERAKHIR SELESAI
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

}