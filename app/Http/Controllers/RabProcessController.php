<?php

namespace App\Http\Controllers;

use App\Models\RabProcess;
use App\Models\RabProcessItem;
use App\Models\JobCategory;
use App\Models\Project;
use App\Models\RabImage;
use App\Models\RabUraianImage;
use App\Models\RabProcessUraian;
use App\Models\RabProcessCategory;
use App\Services\ProjectNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RabProcessController extends Controller
{

public function store(Request $request)
{
    abort_if(auth()->user()->cannot('lihat daftar proyek'), 403);

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
        'items.*.base_price' => 'required|numeric|min:0',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.total' => 'required|numeric|min:0',

        'profit' => 'required|numeric|max:100',
        'overhead' => 'required|numeric|max:100',
        'discount' => 'nullable|numeric|min:0',
        'tax_rate' => 'nullable|numeric|min:0|max:100',
        'shipping' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
    ]);

    $project = null;
    $rab = null;

    DB::transaction(function () use ($request, &$project, &$rab) {

        $project = Project::findOrFail($request->project_id);

        // 🔹 Ambil langsung dari form (hasil JS)
        $subtotal = collect($request->items)->sum(fn($i) => (float) $i['total']);

        $discount = (float) ($request->discount ?? 0);
        $taxRate  = (float) ($request->tax_rate ?? 0);
        $shipping = (float) ($request->shipping ?? 0);

        $subtotalAfterDiscount = max($subtotal - $discount, 0);
        $taxTotal = $subtotalAfterDiscount * ($taxRate / 100);
        $grandTotal = $subtotalAfterDiscount + $taxTotal + $shipping;

        $rab = RabProcess::create([
            'project_id' => $project->id,
            'contact_name' => $request->contact_name,
            'job_location' => $request->job_location,
            'job_duration' => $request->job_duration,

            'base_subtotal' => $subtotal,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'subtotal_after_discount' => $subtotalAfterDiscount,

            'tax_rate' => $taxRate,
            'tax_total' => $taxTotal,
            'profit' => $request->profit,      
            'overhead' => $request->overhead,    
            'shipping' => $shipping,
            'grand_total' => $grandTotal,

            'notes' => $request->notes,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'analisa_version' => Cache::get('job_category_last_updated', 0),
        ]);

        $categoryMap = [];

        foreach ($request->categories as $cat) {

            $category = RabProcessCategory::create([
                'rab_process_id' => $rab->id,
                'name' => $cat['name'],
            ]);

            $categoryMap[$cat['key']] = $category->id;
        }

        $uraianMap = [];

        foreach ($request->items as $item) {
        $key = $item['uraian_key'];

        if(!isset($uraianMap[$key])) {

            $uraian = RabProcessUraian::create([
                'rab_process_id' => $rab->id,
                'job_category_id' => $item['job_category_id'],
                'category_id' => $categoryMap[$item['category_key']],
                'uraian_key' => $key,
                'name' => $item['uraian_name'],
            ]);

            $uraianMap[$key] = $uraian->id;
        }
            RabProcessItem::create([
                'rab_process_id' => $rab->id,
                'uraian_id' => $uraianMap[$key],
                'job_category_id' => $item['job_category_id'],
                'job_name' => $item['job_name'],
                'base_price' => $item['base_price'],
                'satuan' => $item['satuan'],
                'volume' => $item['volume'],
                'price' => $item['price'],   
                'total' => $item['total'],  
            ]);
        }

        foreach($request->uraian_images ?? [] as $uraianKey => $images){

            foreach($images as $imgId){

                RabUraianImage::create([
                    'rab_id' => $rab->id,
                    'uraian_key' => $uraianKey,
                    'image_id' => $imgId
                ]);

            }

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

    $this->notifyProjectEvent($project, 'rab_created');

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
    abort_if(auth()->user()->cannot('ubah data proyek'), 403);
    
    $request->validate([
        'contact_name' => 'required|string',
        'job_location' => 'required|string',
        'job_duration' => 'nullable|string',

        'items' => 'required|array|min:1',
        'items.*.job_category_id' => 'required|exists:job_categories,id',
        'items.*.job_name' => 'required|string',
        'items.*.satuan' => 'required|string',
        'items.*.volume' => 'required|numeric|min:0.01',
        'items.*.base_price' => 'required|numeric|min:0',
        // 'items.*.price' => 'required|numeric|min:0',
        // 'items.*.total' => 'required|numeric|min:0',
        'profit' => 'required|numeric|max:100',
        'overhead' => 'required|numeric|max:100',
        'discount' => 'nullable|numeric|min:0',
        'tax_rate' => 'nullable|numeric|min:0|max:100',
        'shipping' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
    ]);

DB::transaction(function () use ($request, $rab, $project) {

    $rab->items()->delete();

    $subtotal = 0;

    foreach ($request->items as $item) {

        $base = $item['volume'] * $item['base_price'];
        $profitValue = $base * ($item['profit'] / 100);
        $overheadValue = $base * ($item['overhead'] / 100);

        $total = $base + $profitValue + $overheadValue;
        $total = round($total);

        $subtotal += $total;

        $rab->items()->create([
            'job_category_id' => $item['job_category_id'],
            'job_name' => $item['job_name'],
            'satuan' => $item['satuan'],
            'volume' => $item['volume'],
            'base_price' => $item['base_price'],
            'price' => $item['price'],
            'total' => $total,
        ]);
    }

    $discount = (float) ($request->discount ?? 0);
    $taxRate  = (float) ($request->tax_rate ?? 0);
    $shipping = (float) ($request->shipping ?? 0);

    $subtotalAfterDiscount = max($subtotal - $discount, 0);
    $taxTotal = round($subtotalAfterDiscount * ($taxRate / 100));
    $grandTotal = round($subtotalAfterDiscount + $taxTotal + $shipping);

    $rab->update([
        'contact_name' => $request->contact_name,
        'job_location' => $request->job_location,
        'job_duration' => $request->job_duration,
        'profit' => $request->profit,      
        'overhead' => $request->overhead, 
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

protected function notifyProjectEvent(Project $project, string $event)
{
    $cfg = config("project_events.$event");
    if (!$cfg) return;

    $admin    = auth()->user();
    $customer = $project->customer?->user;

    $targets = [];

    if ($admin) {
        $targets['admin'] = $admin;
    }

    if ($customer) {
        $targets['customer'] = $customer;
    }

    foreach ($targets as $role => $user) {
        if (!isset($cfg['message'][$role])) continue;

        ProjectNotifier::notifyUsers(
            [$user],
            ProjectNotifier::makePayload($project, [
                'type'    => $event,
                'role'    => $role,
                'title'   => $cfg['title'],
                'message' => $cfg['message'][$role],
                'url'     => route('projects.create', ['project_id' => $project->id]),
            ])
        );
    }
}

    public function getPackage($id)
    {
        $package = RabProcess::with('items')->findOrFail($id);
        return response()->json($package);
    }

public function items($id)
{
    $rab = RabProcess::with('items.category')->findOrFail($id);

    $grouped = $rab->items
        ->groupBy('job_category_id')
        ->map(function ($rows) {
            return [
                'category' => [
                    'id'   => $rows->first()->category->id,
                    'code' => $rows->first()->category->kode_urut,
                    'name' => $rows->first()->category->nama_pekerjaan,
                ],
                'items' => $rows->values(),
                'subtotal' => $rows->sum('total')
            ];
        })
        ->values();

        return response()->json([
            'header' => [
                'tax_rate' => $rab->tax_rate,
                'discount' => $rab->discount,
                'shipping' => $rab->shipping,
                'subtotal' => $rab->subtotal,
                'subtotal_after_discount' => $rab->subtotal_after_discount,
                'tax_total' => $rab->tax_total,
                'grand_total' => $rab->grand_total,
                'notes' => $rab->notes,
            ],
            'groups' => $grouped
        ]);
}

public function upload(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:4096'
    ]);

    $path = $request->file('image')->store('rab/uraian','public');

    $img = RabImage::create([
        'path' => $path
    ]);

    return response()->json([
        'id' => $img->id,
        'url' => asset('storage/'.$path)
    ]);
}
public function destroy($id)
{
    $img = RabImage::findOrFail($id);

    Storage::disk('public')->delete($img->path);

    $img->delete();

    return response()->json(['success'=>true]);
}
public function uraianImages($uraianId)
{

    $uraian = RabProcessUraian::findOrFail($uraianId);

    $images = RabUraianImage::with('image')
        ->where('uraian_key', $uraian->uraian_key)
        ->get();

    return response()->json(
        $images->map(fn($i)=>[
            'url'=>asset('storage/'.$i->image->path)
        ])
    );
}
public function structure($id)
{
    $rab = RabProcess::with([
        'categories.uraians.items.category',
        'categories.uraians.images.image'
    ])->findOrFail($id);

    return response()->json([
        'meta' => [
            'profit' => $rab->profit,
            'overhead' => $rab->overhead,
            'discount' => $rab->discount,
            'tax_rate' => $rab->tax_rate,
            'shipping' => $rab->shipping,
        ],
        'categories' => $rab->categories
    ]);
}
}