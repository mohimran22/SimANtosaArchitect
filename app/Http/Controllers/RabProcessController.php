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
use App\Services\BuildProcessSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RabProcessController extends Controller
{
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'project_id' => 'required|exists:projects,id',
        'contact_name' => 'required|string|max:255',
        'job_location' => 'required|string|max:255',
        'job_duration' => 'nullable|string',
        'profit' => 'nullable|numeric|min:0|max:100',
        'overhead' => 'nullable|numeric|min:0|max:100',
        'discount' => 'nullable|numeric|min:0',
        'tax_rate' => 'nullable|numeric|min:0|max:100',
        'shipping' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string|max:255',
        'items' => 'required|array|min:1',
        'items.*.floor_name' => 'required|string|max:255',
        'items.*.category_name' => 'required|string|max:255',
        'items.*.job_name' => 'required|string|max:255',
        'items.*.description' => 'nullable|string',
        'items.*.satuan' => 'required|string|max:100',
        'items.*.volume' => 'required|numeric|gt:0',
        'items.*.base_price' => 'required|numeric|min:0',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.total' => 'required|numeric|min:0',
        'items.*.order_no' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {

        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
    }

    DB::beginTransaction();

    try {

        $profit =
            (float) ($request->profit ?? 0);

        $overhead =
            (float) ($request->overhead ?? 0);

        $discount =
            (float) ($request->discount ?? 0);

        $taxRate =
            (float) ($request->tax_rate ?? 0);

        $shipping =
            (float) ($request->shipping ?? 0);

        $baseSubtotal = 0;

        $subtotal = 0;


        foreach ($request->items as $item) {

            $volume =
                (float) $item['volume'];

            $basePrice =
                (float) $item['base_price'];


            $baseTotal =
                $volume * $basePrice;

            $baseSubtotal += $baseTotal;

            $overheadAmount =
                $basePrice * $overhead / 100;

            $profitAmount =
                $basePrice * $profit / 100;

            $price =
                $basePrice
                + $overheadAmount
                + $profitAmount;

            $total =
                $volume * $price;


            $subtotal += $total;
        }

        $subtotalAfterDiscount = max(
            0,
            $subtotal - $discount
        );

        $taxTotal =
            $subtotalAfterDiscount
            * $taxRate
            / 100;
        $grandTotal =
            $subtotalAfterDiscount
            + $taxTotal
            + $shipping;


        $rab = RabProcess::create([

            'project_id' =>
                $request->project_id,

            'contact_name' =>
                $request->contact_name,
            'notes' =>
                $request->notes,
            'job_location' =>
                $request->job_location,
            'job_duration' => $request->job_duration,
            'base_subtotal' =>
                $baseSubtotal,

            'profit' =>
                $profit,

            'overhead' => $overhead,

            'subtotal' =>
                $subtotal,

            'discount' =>
                $discount,

            'subtotal_after_discount' =>
                $subtotalAfterDiscount,

            'tax_rate' =>
                $taxRate,

            'tax_total' =>
                $taxTotal,

            'shipping' =>
                $shipping,

            'grand_total' =>
                $grandTotal,

            'created_by' =>
                auth()->id(),
        ]);

        foreach ($request->items as $index => $item) {

            $volume =
                (float) $item['volume'];

            $basePrice =
                (float) $item['base_price'];

            $overheadAmount =
                $basePrice * $overhead / 100;

            $profitAmount =
                $basePrice * $profit / 100;


            $price =
                $basePrice
                + $overheadAmount
                + $profitAmount;

            $total =
                $volume * $price;


            RabProcessItem::create([

                'rab_process_id' =>
                    $rab->id,

                'floor_name' =>
                    $item['floor_name'],

                'category_name' =>
                    $item['category_name'],

                'job_name' =>
                    $item['job_name'],

                'description' =>
                    $item['description'] ?? null,

                'satuan' =>
                    $item['satuan'],

                'volume' =>
                    $volume,

                'base_price' =>
                    $basePrice,

                'price' =>
                    $price,

                'total' =>
                    $total,

                'order_no' =>
                    $index + 1,
            ]);
        }
        $project = Project::findOrFail($request->project_id);

        $finalLevel = $project->levels()
            ->where('level_name', 'Proses Pengerjaan RAB')
            ->first();

        if ($finalLevel && !$finalLevel->is_completed) {

            $finalLevel->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);
        }
        DB::commit();
        $this->notifyProjectEvent(
            $project,
            'rab_created'
        );

        return redirect()
            ->back()
            ->with(
                'success',
                'RAB berhasil disimpan.'
            );


    } catch (\Throwable $e) {

        DB::rollBack();

        report($e);

        return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                'RAB gagal disimpan: ' . $e->getMessage()
            );
    }
}
public function exportPdf(Project $project)
{
    $rab = $project->rab()->with([
        'items'
    ])->first();

    if (!$rab) abort(404);

    $pdf = Pdf::loadView('rab.pdf', compact('rab', 'project'))
        ->setPaper('A4', 'portrait');

    return $pdf->stream('RENCANA ANGGARAN BIAYA-'.$project->project_name.'.pdf');
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
    $rab = RabProcess::with([
        'items' => function ($query) {
            $query->orderBy('order_no');
        }
    ])->findOrFail($id);

    $subtotal = $rab->items->sum(function ($item) {
        return (float) $item->total;
    });

    $discount = (float) ($rab->discount ?? 0);
    $shipping = (float) ($rab->shipping ?? 0);
    $taxRate = (float) ($rab->tax_rate ?? 0);

    $subtotalAfterDiscount = $subtotal - $discount;

    $taxTotal = $subtotalAfterDiscount * ($taxRate / 100);

    $grandTotal = $subtotalAfterDiscount + $taxTotal + $shipping;

    return response()->json([
        'items' => $rab->items,

        'header' => [
            'tax_rate' => $taxRate,
            'discount' => $discount,
            'shipping' => $shipping,

            'subtotal' => $subtotal,
            'subtotal_after_discount' => $subtotalAfterDiscount,
            'tax_total' => $taxTotal,
            'grand_total' => $grandTotal,
            'extra_discount' => $offer->extra_discount ?? 0,
            'notes' => $rab->notes,
        ],
    ]);
}

public function upload(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:4096',
        'uraian_id' => 'required|exists:rab_process_uraians,id',
        'rab_id' => 'required|exists:rab_process,id'
    ]);

    $path = $request->file('image')->store('rab/uraian', 'public');

    $img = RabImage::create([
        'path' => $path
    ]);

    RabUraianImage::create([
        'rab_id' => $request->rab_id,
        'uraian_id' => $request->uraian_id,
        'image_id' => $img->id
    ]);

    return response()->json([
        'id' => $img->id,
        'path' => $path,
        'url' => asset('storage/' . $path)
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
    $images = RabUraianImage::with('image')
        ->where('uraian_id', $uraianId)
        ->get();

    return response()->json(
        $images->map(fn($i) => [
            'id' => $i->image?->id,
            'url' => $i->image
                ? asset('storage/' . $i->image->path)
                : null
        ])
    );
}
public function structure($id)
{
    $rab = RabProcess::with([
        'items',
    ])->findOrFail($id);

    return response()->json([
        'meta' => [
            'profit' => $rab->profit,
            'overhead' => $rab->overhead,
            'discount' => $rab->discount,
            'tax_rate' => $rab->tax_rate,
            'shipping' => $rab->shipping,
        ],
        'items' => $rab->items
    ]);
}
public function update(
    Request $request,
    Project $project,
    RabProcess $rab
) {
    /*
    |--------------------------------------------------------------------------
    | Pastikan RAB memang milik project dari route
    |--------------------------------------------------------------------------
    */

    if ($rab->project_id !== $project->id) {

        abort(404);

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validator = Validator::make($request->all(), [

        'project_id' =>
            'required|exists:projects,id',

        'contact_name' =>
            'required|string|max:255',

        'job_location' =>
            'required|string|max:255',

        'job_duration' =>
            'nullable|string',

        'profit' =>
            'nullable|numeric|min:0|max:100',

        'overhead' =>
            'nullable|numeric|min:0|max:100',

        'discount' =>
            'nullable|numeric|min:0',

        'tax_rate' =>
            'nullable|numeric|min:0|max:100',

        'shipping' =>
            'nullable|numeric|min:0',
        'notes' => 'nullable|string',

        /*
        |--------------------------------------------------------------------------
        | ITEMS
        |--------------------------------------------------------------------------
        */

        'items' =>
            'required|array|min:1',

        'items.*.id' =>
            'nullable',

        'items.*.floor_name' =>
            'required|string|max:255',

        'items.*.category_name' =>
            'required|string|max:255',

        'items.*.job_name' =>
            'required|string|max:255',

        'items.*.description' =>
            'nullable|string',

        'items.*.satuan' =>
            'required|string|max:100',

        'items.*.volume' =>
            'required|numeric|gt:0',

        'items.*.base_price' =>
            'required|numeric|min:0',

        'items.*.price' =>
            'nullable|numeric|min:0',

        'items.*.total' =>
            'nullable|numeric|min:0',

        'items.*.order_no' =>
            'required|integer|min:1',
    ]);


    if ($validator->fails()) {

        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();

    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT_ID DARI FORM HARUS SESUAI DENGAN ROUTE
    |--------------------------------------------------------------------------
    */

    if ($request->project_id !== $project->id) {

        return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                'Project RAB tidak valid.'
            );

    }


    DB::beginTransaction();


    try {

        /*
        |--------------------------------------------------------------------------
        | GLOBAL RAB VALUES
        |--------------------------------------------------------------------------
        */

        $profit =
            (float) ($request->profit ?? 0);

        $overhead =
            (float) ($request->overhead ?? 0);

        $discount =
            (float) ($request->discount ?? 0);

        $taxRate =
            (float) ($request->tax_rate ?? 0);

        $shipping =
            (float) ($request->shipping ?? 0);


        /*
        |--------------------------------------------------------------------------
        | CALCULATE SUBTOTAL
        |--------------------------------------------------------------------------
        */

        $baseSubtotal = 0;

        $subtotal = 0;


        foreach ($request->items as $item) {

            $volume =
                (float) $item['volume'];

            $basePrice =
                (float) $item['base_price'];


            /*
            |--------------------------------------------------------------------------
            | BASE SUBTOTAL
            |--------------------------------------------------------------------------
            */

            $baseTotal =
                $volume * $basePrice;

            $baseSubtotal += $baseTotal;


            /*
            |--------------------------------------------------------------------------
            | OVERHEAD
            |--------------------------------------------------------------------------
            */

            $overheadAmount =
                $basePrice
                * $overhead
                / 100;


            /*
            |--------------------------------------------------------------------------
            | PROFIT
            |--------------------------------------------------------------------------
            */

            $profitAmount =
                $basePrice
                * $profit
                / 100;


            /*
            |--------------------------------------------------------------------------
            | FINAL PRICE
            |--------------------------------------------------------------------------
            */

            $price =
                $basePrice
                + $overheadAmount
                + $profitAmount;


            /*
            |--------------------------------------------------------------------------
            | ITEM TOTAL
            |--------------------------------------------------------------------------
            */

            $total =
                $volume * $price;


            $subtotal += $total;
        }


        /*
        |--------------------------------------------------------------------------
        | DISCOUNT
        |--------------------------------------------------------------------------
        */

        $subtotalAfterDiscount = max(
            0,
            $subtotal - $discount
        );


        /*
        |--------------------------------------------------------------------------
        | TAX
        |--------------------------------------------------------------------------
        */

        $taxTotal =
            $subtotalAfterDiscount
            * $taxRate
            / 100;


        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        $grandTotal =
            $subtotalAfterDiscount
            + $taxTotal
            + $shipping;


        /*
        |--------------------------------------------------------------------------
        | UPDATE RAB HEADER
        |--------------------------------------------------------------------------
        */

        $rab->update([

            'contact_name' =>
                $request->contact_name,

            'job_location' =>
                $request->job_location,

            'job_duration' =>
                $request->job_duration,
            'notes' => $request->notes,
            'base_subtotal' =>
                $baseSubtotal,

            'profit' =>
                $profit,

            'overhead' =>
                $overhead,

            'subtotal' =>
                $subtotal,

            'discount' =>
                $discount,

            'subtotal_after_discount' =>
                $subtotalAfterDiscount,

            'tax_rate' =>
                $taxRate,

            'tax_total' =>
                $taxTotal,

            'shipping' =>
                $shipping,

            'grand_total' =>
                $grandTotal,
            'updated_by' => auth()->id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | SYNC RAB ITEMS
        |--------------------------------------------------------------------------
        */

        $submittedItemIds = [];


        foreach (
            $request->items
            as $index => $item
        ) {

            $volume =
                (float) $item['volume'];

            $basePrice =
                (float) $item['base_price'];


            /*
            |--------------------------------------------------------------------------
            | HITUNG ULANG PRICE
            |--------------------------------------------------------------------------
            */

            $overheadAmount =
                $basePrice
                * $overhead
                / 100;

            $profitAmount =
                $basePrice
                * $profit
                / 100;


            $price =
                $basePrice
                + $overheadAmount
                + $profitAmount;


            /*
            |--------------------------------------------------------------------------
            | HITUNG ULANG TOTAL
            |--------------------------------------------------------------------------
            */

            $total =
                $volume * $price;


            /*
            |--------------------------------------------------------------------------
            | ITEM LAMA
            |--------------------------------------------------------------------------
            */

            if (!empty($item['id'])) {

                $rabItem = RabProcessItem::where(
                    'id',
                    $item['id']
                )
                    ->where(
                        'rab_process_id',
                        $rab->id
                    )
                    ->first();


                /*
                |--------------------------------------------------------------------------
                | Kalau ID tidak ditemukan / bukan milik RAB ini,
                | jangan update sembarang item.
                |--------------------------------------------------------------------------
                */

                if (!$rabItem) {

                    throw new \Exception(
                        'Item RAB tidak ditemukan atau tidak sesuai dengan RAB.'
                    );

                }


                $rabItem->update([

                    'floor_name' =>
                        $item['floor_name'],

                    'category_name' =>
                        $item['category_name'],

                    'job_name' =>
                        $item['job_name'],

                    'description' =>
                        $item['description'] ?? null,

                    'satuan' =>
                        $item['satuan'],

                    'volume' =>
                        $volume,

                    'base_price' =>
                        $basePrice,

                    'price' =>
                        $price,

                    'total' =>
                        $total,

                    'order_no' =>
                        $index + 1,
                ]);


                $submittedItemIds[] =
                    $rabItem->id;

            }


            /*
            |--------------------------------------------------------------------------
            | ITEM BARU
            |--------------------------------------------------------------------------
            */

            else {

                $rabItem =
                    RabProcessItem::create([

                        'rab_process_id' =>
                            $rab->id,

                        'floor_name' =>
                            $item['floor_name'],

                        'category_name' =>
                            $item['category_name'],

                        'job_name' =>
                            $item['job_name'],

                        'description' =>
                            $item['description'] ?? null,

                        'satuan' =>
                            $item['satuan'],

                        'volume' =>
                            $volume,

                        'base_price' =>
                            $basePrice,

                        'price' =>
                            $price,

                        'total' =>
                            $total,

                        'order_no' =>
                            $index + 1,
                    ]);


                $submittedItemIds[] =
                    $rabItem->id;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE ITEM YANG SUDAH DIHAPUS DI FRONTEND
        |--------------------------------------------------------------------------
        */

        RabProcessItem::where(
            'rab_process_id',
            $rab->id
        )
            ->whereNotIn(
                'id',
                $submittedItemIds
            )
            ->delete();


        DB::commit();


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->back()
            ->with(
                'success',
                'RAB berhasil diperbarui.'
            );


    } catch (\Throwable $e) {

        DB::rollBack();

        report($e);


        return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                'RAB gagal diperbarui: '
                . $e->getMessage()
            );
    }
}
public function loadDraft(RabProcess $rab)
{
    $hasDraft = RabProcessCategory::where('rab_process_id', $rab->id)
        ->where('is_draft', true)
        ->exists();

    $isDraft = $hasDraft;

    $categories = RabProcessCategory::where('rab_process_id', $rab->id)
        ->where('is_draft', $isDraft)
        ->orderBy('order_no')
        ->get();

    $uraians = RabProcessUraian::with([
        'images.image'
    ])
    ->where('rab_process_id', $rab->id)
    ->where('is_draft', $isDraft)
    ->orderBy('order_no')
    ->get()
    ->groupBy('category_id');

    $items = RabProcessItem::where('rab_process_id', $rab->id)
        ->where('is_draft', $isDraft)
        ->orderBy('order_no')
        ->get()
        ->groupBy('uraian_id');

    $result = [
        'meta' => [
            'profit' => $rab->profit,
            'overhead' => $rab->overhead,
        ],
        'categories' => []
    ];

    foreach ($categories as $cat) {

        $catData = [
            'id' => $cat->id,
            'name' => $cat->name,
            'uraians' => []
        ];

        foreach ($uraians[$cat->id] ?? [] as $u) {

            $uData = [
                'id' => $u->id,
                'uraian_key' => $u->uraian_key,
                'name' => $u->name,

                'images' => $u->images->map(function ($pivot) {

                    $image = $pivot->image;

                    if (!$image) {
                        return null;
                    }

                    return [
                        'id' => $image->id,
                        'url' => $image->url,
                    ];

                })->filter()->values()->toArray(),

                'items' => []
            ];

            foreach ($items[$u->id] ?? [] as $it) {

                $uData['items'][] = [
                    'id' => $it->id,
                    'job_category_id' => $it->job_category_id,
                    'satuan' => $it->satuan,
                    'volume' => $it->volume,
                    'base_price' => $it->base_price,
                    'price' => $it->price,
                    'total' => $it->total,
                ];
            }

            $catData['uraians'][] = $uData;
        }

        $result['categories'][] = $catData;
    }

    return response()->json($result);
}

public function reorder(Request $request, RabProcess $rab)
{
    DB::transaction(function () use ($request, $rab) {

        foreach ($request->structure ?? [] as $cat) {

            RabProcessCategory::where('id', $cat['id'])
                ->where('rab_process_id', $rab->id)
                ->update([
                    'order_no' => $cat['order']
                ]);

            foreach ($cat['uraians'] ?? [] as $uraian) {

                RabProcessUraian::where('id', $uraian['id'])
                    ->where('rab_process_id', $rab->id)
                    ->update([
                        'order_no' => $uraian['order']
                    ]);

                foreach ($uraian['items'] ?? [] as $item) {

                    RabProcessItem::where('id', $item['id'])
                        ->where('rab_process_id', $rab->id)
                        ->update([
                            'order_no' => $item['order']
                        ]);
                }
            }
        }
    });

    return response()->json(['status' => 'ok']);
}
}