<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ProductSupplier;
use App\Models\LaborCost;
use App\Models\EquipmentCost;
use App\Models\JobCategoryItem;


class JobCategoryItemController extends Controller
{
    // public function changeSupplier(Request $request, JobCategoryItem $item)
    // {
    //     $request->validate([
    //         'supplier_id' => 'required|exists:suppliers,id'
    //     ]);

    //     $pivot = ProductSupplier::where('product_id', $item->product_id)
    //         ->where('supplier_id', $request->supplier_id)
    //         ->firstOrFail();

    //     $item->update([
    //         'supplier_id' => $request->supplier_id,
    //         'base_unit_price' => $pivot->selling_prices,
    //         'total_price' => $item->coefisien * $pivot->selling_prices,
    //     ]);

    //     return response()->json([
    //         'base_unit_price' => $item->base_unit_price,
    //         'total_price' => $item->total_price,
    //     ]);
    // }

    public function changeSupplier(Request $request, JobCategoryItem $item)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        $pivot = ProductSupplier::where('product_id', $item->product_id)
            ->where('supplier_id', $request->supplier_id)
            ->firstOrFail();

        $item->update([
            'supplier_id' => $request->supplier_id,
            'base_unit_price' => $pivot->selling_prices,
            'total_price' => $item->coefisien * $pivot->selling_prices,
        ]);

        // Hitung ulang subtotal category ini
        $items = JobCategoryItem::where('job_category_id', $item->job_category_id)->get();

        $totalLabor = $items->where('category','labor')->sum('total_price');
        $totalProduct = $items->where('category','product')->sum('total_price');
        $totalEquipment = $items->where('category','equipment')->sum('total_price');

        $subTotal = $totalLabor + $totalProduct + $totalEquipment;

        $jobCategory = $item->jobCategory;

        $overheadValue = $subTotal * ($jobCategory->overhead_percent / 100);
        $profitValue   = $subTotal * ($jobCategory->profit_percent / 100);
        $grandTotal    = $subTotal + $overheadValue + $profitValue;
        $jobCategory->update([
            'subtotal' => $subTotal,
            'overhead_value' => $overheadValue,
            'profit_value' => $profitValue,
            'grand_total' => $grandTotal,
        ]);

        return response()->json([
            'item' => [
                'id' => $item->id,
                'base_unit_price' => $item->base_unit_price,
                'total_price' => $item->total_price,
            ],
            'summary' => [
                'subtotal' => $subTotal,
                'overhead_value' => $overheadValue,
                'profit_value' => $profitValue,
                'grand_total' => $grandTotal,
            ]
        ]);
    }

    public function changeUraian(Request $request, JobCategoryItem $item)
{
    $value = $request->value; // contoh: product_12

    [$type, $id] = explode('_', $value);

    DB::transaction(function () use ($item, $type, $id) {

        if ($type === 'product') {

            $pivot = ProductSupplier::with('product')->findOrFail($id);

            $item->update([
                'product_id'           => $pivot->product_id,
                'product_supplier_id'  => $pivot->id,
                'labor_cost_id'        => null,
                'equipment_cost_id'    => null,

                'name' => $pivot->product->name,
                'code' => $pivot->product->sku_code,
                'unit' => $pivot->product->unit ?? '-',
            ]);

        } elseif ($type === 'labor') {

            $labor = LaborCost::findOrFail($id);

            $item->update([
                'labor_cost_id'        => $labor->id,
                'product_id'           => null,
                'product_supplier_id'  => null,
                'equipment_cost_id'    => null,

                'name' => $labor->description,
                'code' => $labor->code ?? '-',
                'unit' => $labor->unit,
            ]);

        } elseif ($type === 'equipment') {

            $eq = EquipmentCost::findOrFail($id);

            $item->update([
                'equipment_cost_id'    => $eq->id,
                'product_id'           => null,
                'product_supplier_id'  => null,
                'labor_cost_id'        => null,

                'name' => $eq->name,
                'code' => $eq->code ?? '-',
                'unit' => $eq->unit,
            ]);
        }

        // 🔥 RECALC
        \App\Services\RabRecalculator::recalcItemAndParent($item);
    });

    $item->refresh();

    return response()->json([
        'success' => true,
        'item' => [
            'base_unit_price' => $item->base_unit_price,
            'total_price'     => $item->total_price,
        ],
        'summary' => [
            'subtotal'       => $item->jobCategory->subtotal,
            'overhead_value' => $item->jobCategory->overhead_value,
            'profit_value'   => $item->jobCategory->profit_value,
            'grand_total'    => $item->jobCategory->grand_total,
        ]
    ]);
}

}


        //  if  ($sheet1) {
        //     $rows = $sheet1->toArray();

        //     foreach ($rows as $index => $row) {
        //         if ($index === 0) continue;

        //         $province = Province::whereRaw("TRIM(name) = ?", [F::cleanFk($row[6])])->first();
        //         $city = City::whereRaw("TRIM(name) = ?", [F::cleanFk($row[7])])->first();
        //         $district = District::whereRaw("TRIM(name) = ?", [F::cleanFk($row[8])])->first();
        //         $sub_district = SubDistrict::whereRaw("TRIM(name) = ?", [F::cleanFk($row[9])])->first();
        //         $postal_code = PostalCode::whereRaw("TRIM(postal_code) = ?", [F::cleanFk($row[10])])->first();


        //         logger([
        //             'Province' => $province?->name,
        //             'City' => $city?->name,
        //             'District' => $district?->name,
        //             'SubDistrict' => $sub_district?->name,
        //             'PostalCode' => $postal_code?->postal_code
        //         ]);

        //         // Parse join_date
        //         $joinDate = null;
        //         if (isset($row[12]) && $row[12] !== '') {
        //             if (is_numeric($row[12])) {
        //                 $joinDate = ExcelDate::excelToDateTimeObject($row[12])->format('Y-m-d');
        //             } else {
        //                 try {
        //                     $joinDate = Carbon::createFromFormat('d/m/Y', $row[12])->format('Y-m-d');
        //                 } catch (\Exception $e) {
        //                     try {
        //                         $joinDate = Carbon::parse($row[12])->format('Y-m-d');
        //                     } catch (\Exception $e2) {
        //                         logger('Join Date Parse FAILED: ' . $row[12]);
        //                     }
        //                 }
        //             }
        //         }

        //         // Parse expired_date
        //         $expiredDate = null;
        //         if (isset($row[13]) && $row[13] !== '') {
        //             if (is_numeric($row[13])) {
        //                 $expiredDate = ExcelDate::excelToDateTimeObject($row[13])->format('Y-m-d');
        //             } else {
        //                 try {
        //                     $expiredDate = Carbon::createFromFormat('d/m/Y', $row[13])->format('Y-m-d');
        //                 } catch (\Exception $e) {
        //                     try {
        //                         $expiredDate = Carbon::parse($row[13])->format('Y-m-d');
        //                     } catch (\Exception $e2) {
        //                         logger('Expired Date Parse FAILED: ' . $row[13]);
        //                     }
        //                 }
        //             }
        //         }

        //         try {
        //             License::create([
        //                 'id' => Str::uuid(),
        //                 'license_id' => $row[1] ?? null,
        //                 'license_type' => $row[2] ?? null,
        //                 'name' => $row[3] ?? null,
        //                 'email' => $row[4] ?? null,
        //                 'address' => $row[5] ?? null,
        //                 'province_id' => $province?->id,
        //                 'city_id' => $city?->id,
        //                 'district_id' => $district?->id,
        //                 'sub_district_id' => $sub_district?->id,
        //                 'postal_codes' => $postal_code?->id,
        //                 'phone' => $row[11] ?? null,
        //                 'join_date' => $joinDate,
        //                 'expired_date' => $expiredDate,
        //                 'contract_agreement_number' => $row[14] ?? null,
        //                 'status' => $row[15] ?? null,
        //             ]);
        //             $totalInserted++;
        //         } catch (\Exception $e) {
        //             logger('INSERT ERROR SHEET1: ' . $e->getMessage());
        //         }
        //     }
        // }

    

        // $sheet2 = $spreadsheet->getSheetByName('Akun Sosial Media');
        // if ($sheet2) {
        //         $rows = $sheet2->toArray();

        //         foreach ($rows as $index => $row) {
        //             if ($index === 0) continue;

        //             $license = License::where('license_id', trim($row[1] ?? ''))->first();
        //             if ($license) {
        //                 $license->update([
        //                     'instagram' => $row[5] ?? null,
        //                     'facebook_page' => $row[7] ?? null,
        //                     'tiktok' => $row[9] ?? null,
        //                     'youtube' => $row[11] ?? null,
        //                     'landig_page_student_registration' => $row[13] ?? null,
        //                     'google_maps' => $row[17] ?? null,
        //                 ]);
        //                 $totalInserted++;
        //             }
        //         }
        //     }
 
