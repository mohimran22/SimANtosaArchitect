<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferBuildRequest;
use App\Http\Requests\OfferBuildUpdateRequest;
use App\Models\Offer;
use App\Models\OfferItem;
use App\Models\RabProcess;
use App\Models\Project;
use App\Models\ProjectLevel;
use App\Models\OfferCounter;
use App\Services\ProjectNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OfferBuildController extends Controller
{
public function store(OfferBuildRequest $request)
{
    abort_if(auth()->user()->cannot('lihat daftar proyek'), 403);

    $data = $request->validated();

    $rab = RabProcess::with([
        'items' => function ($query) {
            $query->orderBy('order_no');
        }
    ])->findOrFail($data['rab_process_id']);


    DB::beginTransaction();

    try {

        $extraDiscount = (float) ($data['extra_discount'] ?? 0);
        $subtotal = (float) $rab->subtotal;
        $discount = (float) ($rab->discount ?? 0);
        $subtotalAfterDiscount = $subtotal - $discount;
        $taxRate = (float) ($rab->tax_rate ?? 0);
        $totalTax = $subtotalAfterDiscount * ($taxRate / 100);
        $shipping = (float) ($rab->shipping ?? 0);
        $grandTotalRab = $subtotalAfterDiscount + $totalTax + $shipping;
        $roundedTotal = floor($grandTotalRab / 1000000) * 1000000;
        $extraDiscount = (float) ($data['extra_discount'] ?? 0);
        $grandTotal = max(0, $roundedTotal - $extraDiscount);
        $offer = Offer::create([

            'project_id'     => $data['project_id'],
            'rab_process_id' => $rab->id,

            'offer_number' => $this->generateOfferNumber('BLD'),
            'offer_date'   => $data['offer_date'],

            'contact_name' => $data['contact_name'],

            'volume'      => $rab->volume,
            'price_meter' => $rab->price_meter,

            'total_price' => $rab->volume * $rab->price_meter,

            'subtotal' => $subtotal,

            'discount' =>$discount,

            'subtotal_after_discount' => $subtotalAfterDiscount,

            'extra_discount' => $extraDiscount,

            'tax_rate' => $taxRate,

            'total_tax' => $totalTax,

            'shipping' =>$shipping,

            'grand_total' => $grandTotal,

            'notes' => $data['notes'] ?? null,

            'created_by' => auth()->id(),
        ]);

        foreach ($rab->items as $item) {

            OfferItem::create([

                'offer_id' => $offer->id,

                'floor_name' => $item->floor_name,

                'category_name' => $item->category_name,

                'item_name' => $item->job_name,

                'description' => $item->description,

                'volume' => $item->volume,

                'satuan' => $item->satuan,

                'price' => $item->price,

                'total' => $item->total,

                'sort_order' => $item->order_no,
            ]);
        }

        ProjectLevel::where([
            'project_id'  => $data['project_id'],
            'level_order' => 4,
        ])->update([
            'is_completed' => true
        ]);

        ProjectLevel::where([
            'project_id'  => $data['project_id'],
            'level_order' => 5,
        ])->update([
            'is_started' => true
        ]);


        DB::commit();

        $creatorUser = auth()->user();

        $project = $offer
            ->load('project.customer.user')
            ->project;


        $event = 'offerbuild_created';

        $cfg = config(
            "project_events.offerbuild_created"
        );


        if (!$cfg) {
            throw new \Exception(
                "Config project_events.$event not found"
            );
        }


        $targets = [
            'created_self' => $creatorUser,
        ];


        if ($project->customer?->user) {
            $targets['customer'] =
                $project->customer->user;
        }


        foreach ($targets as $key => $user) {

            if (!$user) {
                continue;
            }

            $role = null;


            if ($user->id === $creatorUser->id) {

                $role = 'created_self';

            } elseif (
                $project->customer?->user &&
                $user->id ===
                    $project->customer->user->id
            ) {

                $role = 'customer';
            }


            if (
                !$role ||
                !isset($cfg['message'][$role])
            ) {
                continue;
            }


            ProjectNotifier::notifyUsers(
                [$user],

                ProjectNotifier::makePayload(
                    $project,
                    [
                        'type' =>
                            $event,

                        'role' =>
                            $role,

                        'title' =>
                            $cfg['title'],

                        'message' =>
                            $cfg['message'][$role],

                        'url' =>
                            route(
                                'projects.create',
                                [
                                    'project_id' =>
                                        $project->id
                                ]
                            ),
                    ]
                )
            );
        }


        return redirect()
            ->route(
                'projects.create',
                [
                    'project_id' =>
                        $offer->project_id
                ]
            )
            ->with(
                'success',
                'Penawaran berhasil disimpan!'
            );


    } catch (\Exception $e) {

        DB::rollBack();

        \Log::error($e);

        return back()
            ->withErrors(
                'Terjadi kesalahan saat menyimpan penawaran'
            );
    }
}

protected function generateOfferNumber(string $type): string
{

        $now = now();
        $yearFull  = $now->format('Y'); // 2026
        $yearShort = $now->format('y'); // 26
        $bulanRomawi = \App\Helpers\GeneralHelper::bulanRomawi($now->month);

        $counter = OfferCounter::where('type', $type)
            ->where('year', $yearFull)
            ->lockForUpdate()
            ->first();

        if (!$counter) {
            $counter = OfferCounter::create([
                'type'        => $type,
                'year'        => $yearFull,
                'last_number' => 0,
            ]);
        }

        $next = $counter->last_number + 1;

        $counter->update([
            'last_number' => $next,
        ]);

        $nomorUrut = str_pad($next, 3, '0', STR_PAD_LEFT);

        return "PH/$type/$yearShort/$bulanRomawi/$nomorUrut";

}

public function update(OfferBuildUpdateRequest $request, $id)
{
    abort_if(
        auth()->user()->cannot('ubah data proyek'),
        403
    );

    $data = $request->validated();

    $offer = Offer::findOrFail($id);

    $rab = RabProcess::with([
        'items' => function ($query) {
            $query->orderBy('order_no');
        }
    ])->findOrFail($data['rab_process_id']);

    $subtotal = (float) (
        $data['subtotal']
        ?? $rab->subtotal
        ?? 0
    );

    $discount = (float) (
        $data['discount']
        ?? $rab->discount
        ?? 0
    );

    $subtotalAfterDiscount = $subtotal - $discount;

    $taxRate = (float) (
        $data['tax_rate']
        ?? $rab->tax_rate
        ?? 0
    );

    $taxTotal = $subtotalAfterDiscount * ($taxRate / 100);

    $shipping = (float) (
        $data['shipping']
        ?? $rab->shipping
        ?? 0
    );

    $grandTotalRab = $subtotalAfterDiscount + $taxTotal + $shipping;

    $roundedTotal = floor($grandTotalRab / 1000000) * 1000000;
    $extraDiscount = (float) ($data['extra_discount'] ?? 0);

    $grandTotalOffer = max(0, $roundedTotal - $extraDiscount);

    $offer->update([

        'project_id' => $data['project_id'],
        'offer_number' => $data['offer_number'],
        'offer_date' => $data['offer_date'],
        'contact_name' => $data['contact_name'] ?? null,
        'rab_process_id' => $rab->id,
        'volume' => $rab->volume,
        'satuan' => $rab->satuan ?? null,
        'price_meter' => $rab->price_meter,
        'total_price' => $rab->volume * $rab->price_meter,
        'subtotal' => $subtotal,
        'discount' => $discount,
        'subtotal_after_discount' => $subtotalAfterDiscount,
        'tax_rate' => $taxRate,
        'tax_total' => $taxTotal,
        'shipping' => $shipping,
        'extra_discount' => $extraDiscount,
        'grand_total' => $grandTotalOffer,
        'notes' => $data['notes'] ?? null,
    ]);

    $offer->items()->delete();

    foreach ($rab->items as $item) {

        $offer->items()->create([

            'floor_name' => $item->floor_name,
            'category_name' => $item->category_name,
            'item_name' => $item->job_name,
            'description' => $item->description,
            'volume' => $item->volume,
            'satuan' => $item->satuan,
            'price' => $item->price,
            'total' => $item->total,
            'sort_order' => $item->order_no,
        ]);
    }

    return back()->with(
        'success',
        'Data Penawaran berhasil diperbarui.'
    );
}

public function printPdf(Project $project)
{
    $project->load([
        'offer.rab.items'
    ]);

    $offer = $project->offer;
    $rab   = $offer?->rab;

    if (!$rab) {
        abort(404);
    }

    $items = $rab->items->sortBy('order_no')->values();

    $grouped = $items
        ->groupBy('floor_name')
        ->map(function ($floorItems) {

            return $floorItems
                ->groupBy('category_name')
                ->map(function ($categoryItems) {

                    return [
                        'items' =>
                            $categoryItems
                                ->sortBy('order_no')
                                ->values(),

                        'subtotal' =>
                            $categoryItems->sum('total'),
                    ];

                });

        });

    $safeName = str_replace(
        ['/', '\\'],
        '-',
        $offer->offer_number
    );

    $filename = 'Penawaran-' . $safeName . '.pdf';

    $pdf = Pdf::loadView(
        'offer.buildpdf',
        compact(
            'offer',
            'rab',
            'project',
            'grouped'
        )
    )
    ->setPaper('A4', 'portrait');

    return $pdf->stream($filename);
}
// private function generateOfferNumber()
// {
//     $tahunFull = date('Y');        // 2026
//     $tahun = date('y');            // 26
//     $bulan = date('n');            // 1-12
//     $romawiBulan = \App\Helpers\GeneralHelper::bulanRomawi($bulan);

//     // Ambil nomor terakhir di tahun ini saja
//     $lastOffer = \App\Models\Offer::whereYear('offer_date', $tahunFull)
//         ->orderBy('id', 'DESC')
//         ->first();

//     if ($lastOffer) {
//         // PH/DSN/26/I/001 → ambil 001
//         $explode = explode('/', $lastOffer->offer_number);
//         $lastNumber = intval(end($explode)) + 1;
//     } else {
//         // Kalau belum ada di tahun ini → mulai dari 1
//         $lastNumber = 1;
//     }

//     // Format ke 3 digit: 1 → 001
//     $nomorUrut = str_pad($lastNumber, 3, '0', STR_PAD_LEFT);

//     return "PH/build/$tahun/$romawiBulan/$nomorUrut";
// }

}
