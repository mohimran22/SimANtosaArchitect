<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\OfferItem;
use App\Models\Project;
use App\Models\ProjectLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
public function store(OfferRequest $request)
{
    $data = $request->validated();

    $project = Project::findOrFail($data['project_id']);

    DB::beginTransaction();

    try {
        // HITUNG TOTAL
        $subtotal = $data['total_price'] ?? 0;
        $discount = $data['discount'] ?? 0;

        $subtotalAfterDiscount = $subtotal - $discount;
        $totalTax = $subtotalAfterDiscount * (($data['tax_rate'] ?? 0) / 100);
        $grandTotal = $subtotalAfterDiscount + $totalTax + ($data['shipping'] ?? 0);

        // SIMPAN OFFER
        $offer = Offer::create([
            'project_id'        => $data['project_id'],
            'design_package_id' => $data['design_package_id'],
            'offer_number'      => $data['offer_number'],
            'offer_date'        => $data['offer_date'],
            'contact_name'      => $data['contact_name'],

            'volume'            => $data['volume'],
            'satuan'            => $data['satuan'],
            'price_meter'       => $data['price_meter'],
            'total_price'       => $subtotal,

            'discount'          => $discount,
            'tax_rate'          => $data['tax_rate'],
            'total_tax'         => $totalTax,

            'shipping'          => $data['shipping'],
            'grand_total'       => $grandTotal,

            'notes'             => $data['notes'] ?? null,
            'created_by'        => auth()->id(),
        ]);

        // SIMPAN ITEM
        if ($request->has('items')) {
            foreach ($request->items as $i => $item) {
                OfferItem::create([
                    'offer_id'    => $offer->id,
                    'item_name'   => $item['item_name'],
                    'category'    => $item['category'] ?? null,
                ]);
            }
        }

        // UPDATE PROJECT LEVEL
        ProjectLevel::where([
            'project_id'  => $data['project_id'],
            'level_order' => 4,
        ])->update(['is_completed' => true]);

        ProjectLevel::where([
            'project_id'  => $data['project_id'],
            'level_order' => 5,
        ])->update(['is_started' => true]);

        DB::commit();

        return redirect()
            ->route('projects.create', ['project_id' => $offer->project_id])
            ->with('success', 'Penawaran berhasil disimpan!');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->withErrors($e->getMessage());
    }
}

}
