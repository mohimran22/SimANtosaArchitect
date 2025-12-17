<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\OfferItem;
use App\Models\Project;
use App\Models\ProjectLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'offer_number'      => $data['offer_number'] ?: $this->generateOfferNumber(),
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
            foreach ($request->items as $item) {
                if (!$item['item_name']) continue; // skip kategori
                
                OfferItem::create([
                    'offer_id'  => $offer->id,
                    'item_name' => $item['item_name'],
                    'category'  => $item['category'],
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

private function generateOfferNumber()
{
    $tahun = date('Y');
    $bulan = date('n'); // 1-12
    $romawiBulan = \App\Helpers\GeneralHelper::bulanRomawi($bulan);

    // Ambil nomor terakhir bulan & tahun ini
    $lastOffer = \App\Models\Offer::whereYear('offer_date', $tahun)
        ->whereMonth('offer_date', $bulan)
        ->orderBy('offer_number', 'DESC')
        ->first();

    if ($lastOffer) {
        // Ambil angka urut terakhir (003 → 4)
        $explode = explode('/', $lastOffer->offer_number);
        $lastNumber = intval(end($explode)) + 1;
    } else {
        $lastNumber = 1;
    }

    // Format ke 3 digit
    $nomorUrut = str_pad($lastNumber, 3, '0', STR_PAD_LEFT);

    return "PH/SO/$tahun/$romawiBulan/$nomorUrut";
}

public function update(Request $request, $id)
{
    $offer = Offer::findOrFail($id);

    // ============================
    // VALIDATION
    // ============================
    $request->validate([
        'project_id'        => 'required|uuid|exists:projects,id',
        'offer_number'      => 'required|string',
        'offer_date'        => 'required|date',
        'contact_name'      => 'nullable|string',

        'design_package_id' => 'required|uuid|exists:design_packages,id',
        'volume'            => 'nullable|numeric',
        'satuan'            => 'nullable|string',
        'price_meter'       => 'nullable|numeric',
        'total_price'       => 'nullable|numeric',

        'discount'          => 'nullable|numeric',
        'tax_rate'          => 'nullable|numeric',
        'shipping'          => 'nullable|numeric',

        'notes'             => 'nullable|string',
        'items'             => 'array',
    ]);

    // ============================
    // UPDATE MAIN OFFER
    // ============================
    $offer->update([
        'project_id'               => $request->project_id,
        'offer_number'             => $request->offer_number,
        'offer_date'               => $request->offer_date,
        'contact_name'             => $request->contact_name,

        'design_package_id'        => $request->design_package_id,
        'volume'                   => $request->volume,
        'satuan'                   => $request->satuan,
        'price_meter'              => $request->price_meter,
        'total_price'              => $request->total_price,

        'subtotal'                 => $request->total_price,
        'discount'                 => $request->discount,
        'subtotal_after_discount'  => $request->total_price - $request->discount,

        'tax_rate'                 => $request->tax_rate,
        'tax_total'                => ($request->total_price - $request->discount) * ($request->tax_rate / 100),

        'shipping'                 => $request->shipping,
        'grand_total'              =>
            ($request->total_price - $request->discount) +
            (($request->total_price - $request->discount) * ($request->tax_rate / 100)) +
            $request->shipping,

        'notes'                    => $request->notes,
    ]);

    // ============================
    // REPLACE OFFER ITEMS
    // ============================
    $offer->items()->delete();

    if ($request->items && count($request->items) > 0) {
        foreach ($request->items as $item) {
            $offer->items()->create([
                'category'   => $item['category'],
                'item_name'  => $item['item_name'],
                'volume'     => $request->volume ?? 0,
                'satuan'     => $request->satuan ?? '-',
                'price'      => $request->price_meter ?? 0,
                'total'      => $request->price_meter * $request->volume,
            ]);
        }
    }

    return back()->with('success', 'Data Penawaran berhasil diperbarui.');
}

public function printPdf(Offer $offer)
{
    // EAGER LOAD semua relasi yang dibutuhkan PDF
    $offer->load([
        'package',
        'items',
        'project',
        'project.customer',
        'project.employee'
    ]);

        // Amanin nama file PDF
    $safeName = str_replace(['/', '\\'], '-', $offer->offer_number);

    $filename = 'Penawaran-'.$safeName.'.pdf';

    $pdf = Pdf::loadView('offer.pdf', compact('offer'))
              ->setPaper('A4', 'portrait');

    return $pdf->stream($filename);
}


}
