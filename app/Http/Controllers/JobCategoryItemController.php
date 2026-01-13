<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\AhspGroup;
use App\Models\Ahsp;
use App\Models\ProductSupplier;
use App\Models\JobCategoryItem;


class JobCategoryItemController extends Controller
{
        public function changeSupplier(Request $request, JobCategoryItem $item)
{
    $supplierId = $request->supplier_id;

    $pivot = ProductSupplier::where('product_id', $item->product_id)
        ->where('supplier_id', $supplierId)
        ->firstOrFail();

    $item->update([
        'supplier_id' => $supplierId,
        'base_unit_price' => $pivot->selling_prices,
        'total_price' => $item->coefisien * $pivot->selling_prices,
    ]);

    return response()->json(['success' => true]);
}

    public function create(AhspGroup $ahspGroup)
    {
        return view('ahsps.create', compact('ahspGroup'));
    }

    public function store(Request $request, AhspGroup $ahspGroup)
    {
        $data = $request->validate([
            'kode' => 'required|string|max:20',
            'kode_urut' => 'required|string|max:50',
            'nama_pekerjaan' => 'required|string',
            'satuan' => 'required|string|max:10'
        ]);

        $ahspGroup->ahsps()->create($data);

        return redirect()
            ->route('ahsp-groups.show', $ahspGroup)
            ->with('success', 'AHSP berhasil ditambahkan');
    }

    public function edit(Ahsp $ahsp)
    {
        return view('ahsps.edit', compact('ahsp'));
    }

    public function update(Request $request, Ahsp $ahsp)
    {
        $data = $request->validate([
            'kode' => 'required|string|max:20',
            'kode_urut' => 'required|string|max:50',
            'nama_pekerjaan' => 'required|string',
            'satuan' => 'required|string|max:10'
        ]);

        $ahsp->update($data);

        return back()->with('success', 'AHSP berhasil diupdate');
    }

    public function destroy(Ahsp $ahsp)
    {
        $group = $ahsp->group;
        $ahsp->delete();

        return redirect()
            ->route('ahsp-groups.show', $group)
            ->with('success', 'AHSP berhasil dihapus');
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
 
