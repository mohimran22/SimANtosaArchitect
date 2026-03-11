<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Models\JobCategoryItem;
use App\Models\LaborCost;
use App\Models\EquipmentCost;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use DB;

class JobCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $jobs = JobCategory::select('*')
                ->orderByRaw("
                    lower(split_part(kode_urut, '.', 1)),
                    (
                        SELECT array_agg(val::int)
                        FROM regexp_split_to_table(
                            trim(trailing '.' from kode_urut),
                            '\\.'
                        ) AS val
                        WHERE val ~ '^[0-9]+$'
                    )
                ");

            return DataTables::of($jobs)
                ->addIndexColumn() // untuk kolom No
                ->editColumn('grand_total', function ($row) {
                    return 'Rp ' . number_format($row->grand_total ?? 0, 0, ',', '.');
                })
                ->addColumn('aksi', function ($row) {
                    return '
                        <a href="'.route('job-categories.edit', $row->id).'" 
                        class="btn btn-sm btn-dark">
                            <i class="ti ti-edit"></i>
                        </a>

                        <form action="'.route('job-categories.destroy', $row->id).'"
                            method="POST" class="d-inline"
                            onsubmit="return confirm(\'Hapus data ini?\')">
                            '.csrf_field().method_field('DELETE').'
                            <button class="btn btn-sm btn-dark">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['aksi']) // penting!
                ->make(true);
        }

        return view('job-categories.index');
    }
    
    public function create()
    {
        $groups = JobCategory::select('nama_group')
            ->distinct()
            ->orderBy('nama_group')
            ->pluck('nama_group');

        return view('job-categories.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'bidang' => 'required|string|max:50',
            'kode_group' => 'required|string|max:50',
            'nama_group' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'kode_urut' => 'required|string|max:100|unique:job_categories,kode_urut',
            'nama_pekerjaan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        JobCategory::create($data);

        return redirect()
            ->route('job-categories.index')
            ->with('success', 'Data pekerjaan berhasil ditambahkan.');
    }

    public function edit(JobCategory $jobCategory)
    {
        $groups = JobCategory::select('bidang','nama_group')
        ->distinct()
        ->orderBy('bidang')
        ->get()
        ->groupBy('bidang');

        return view(
            'job-categories.edit',
            compact('jobCategory', 'groups')
        );
    }

    public function update(Request $request, JobCategory $jobCategory)
    {
        $data = $request->validate([
            'bidang' => 'required|string|max:50',
            'kode_group' => 'required|string|max:50',
            'nama_group' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'kode_urut' => 'required|string|max:100|unique:job_categories,kode_urut,' . $jobCategory->id,
            'nama_pekerjaan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        $jobCategory->update($data);

        return back()->with('success', 'Data pekerjaan berhasil diperbarui.');
    }

    public function destroy(JobCategory $jobCategory)
    {
        $jobCategory->delete();

        return redirect()
            ->route('job-categories.index')
            ->with('success', 'Data pekerjaan berhasil dihapus.');
    }

    public function addItem(Request $request, JobCategory $jobCategory)
    {
        $data = $request->validate([
            'category'          => 'required|in:product,labor,equipment',
            'coefisien'         => 'required|numeric|min:0',
            'base_unit_price'   => 'required|numeric|min:0',

            'product_id'        => 'nullable|exists:products,id',
            'labor_cost_id'     => 'nullable|exists:labor_costs,id',
            'equipment_cost_id' => 'nullable|exists:equipment_costs,id',
            'supplier_id'        => 'nullable|exists:suppliers,id',
            'code'              => 'nullable|string|max:50',
            'unit'              => 'nullable|string|max:50',
            'name'              => 'required|string|max:255',
        ]);

        $data['job_category_id'] = $jobCategory->id;
        $data['total_price']     = $data['coefisien'] * $data['base_unit_price'];

        JobCategoryItem::create($data);

        $this->recalcJobCategory($jobCategory);

        return back()->with('success', 'Item pekerjaan berhasil ditambahkan.');
    }

    public function saveOverheadProfit(Request $request, JobCategory $jobCategory)
    {
        $data = $request->validate([
            'overhead_percent' => 'nullable|numeric|min:0',
            'profit_percent'   => 'nullable|numeric|min:0',
            'overhead_value' => 'nullable|numeric|min:0',
            'profit_value'   => 'nullable|numeric|min:0',
            'subtotal' => 'nullable|numeric|min:0',
            'grand_total'   => 'nullable|numeric|min:0',
        ]);

        $jobCategory->update([
            'overhead_percent' => $data['overhead_percent'] ?? 0,
            'profit_percent'   => $data['profit_percent'] ?? 0,
            'overhead_value' => $data['overhead_value'] ?? 0,
            'profit_value'   => $data['profit_value'] ?? 0,
            'subtotal' => $data['subtotal'] ?? 0,
            'grand_total'   => $data['grand_total'] ?? 0,
        ]);

        $this->recalcJobCategory($jobCategory);

        return response()->json([
            'success' => true
        ]);
    }

    public function updateItem(Request $request, JobCategoryItem $item)
    {
        $data = $request->validate([
            'category' => 'nullable|string|max:255',
            'item_name' => 'required|string|max:255',
            'is_optional' => 'nullable'
        ]);

        $data['is_optional'] = $request->has('is_optional');

        $item->update($data);

        return back()->with('success', 'Item berhasil diperbarui.');
    }

    public function deleteItem(JobCategoryItem $item)
    {
        $jobCategory = $item->jobCategory; // ambil parent

        $item->delete();

        // 🔥 AUTO RECALC
        $this->recalcJobCategory($jobCategory);

        return back()->with('success', 'Item berhasil dihapus.');
    }


    private function recalcJobCategory(JobCategory $jobCategory)
    {
        $subTotal = $jobCategory->items()->sum('total_price');

        $overheadPercent = $jobCategory->overhead_percent ?? 0;
        $profitPercent   = $jobCategory->profit_percent ?? 0;

        $overheadValue = $subTotal * ($overheadPercent / 100);
        $profitValue   = $subTotal * ($profitPercent / 100);

        $grandTotal = $subTotal + $overheadValue + $profitValue;

        $jobCategory->update([
            'subtotal'       => $subTotal,
            'overhead_value' => $overheadValue,
            'profit_value'   => $profitValue,
            'grand_total'    => $grandTotal,
        ]);
        Cache::put('job_category_last_updated', now()->timestamp);
    }

    public function getItems($type)
    {
        return match ($type) {
            'product' => Product::select('id', 'name')->get(),

            'labor' => LaborCost::selectRaw(
                'id, description as name'
            )->get(),

            'equipment' => EquipmentCost::selectRaw(
                'id, description as name'
            )->get(),
        };
    }

    public function getItemDetail($type, $id)
    {
        return match ($type) {

        // 'product' => $this->getProductWithSupplierPrice($id),

            'labor' => LaborCost::select(
                'id',
                'description as name',
                'code',
                'unit',
                'base_unit_price as price'
            )->findOrFail($id),

            'equipment' => EquipmentCost::select(
                'id',
                'description as name',
                'code',
                'unit',
                'base_unit_price as price'
            )->findOrFail($id),

            'supplier' => $this->getSuppliersByProduct($id),
        };
    }

    // protected function getProductWithSupplierPrice($productId)
    // {
    //     $product = Product::select(
    //         'id',
    //         'name',
    //         'sku_code as code',
    //         'unit_1_name as unit'
    //     )->findOrFail($productId);

    //     $supplier = ProductSupplier::where('product_id', $productId)
    //         ->orderBy('selling_prices') // bisa diganti: supplier utama
    //         ->first();

    //     return [
    //         'id'    => $product->id,
    //         'name'  => $product->name,
    //         'code'  => $product->code,
    //         'unit'  => $product->unit,
    //         'price' => $supplier?->selling_prices ?? 0,
    //     ];
    // }

public function getSuppliersByProduct($productId)
{
    return Supplier::whereHas('products', function ($q) use ($productId) {
            $q->where('products.id', $productId);
        })
        ->with(['products' => function ($q) use ($productId) {
            $q->where('products.id', $productId);
        }])
        ->get()
        ->map(function ($supplier) {
            $pivot = $supplier->products->first()->pivot;

            return [
                'id'    => $supplier->id,
                'name'  => $supplier->name,
                'price' => $pivot->selling_prices,
            ];
        });
}

// public function getSuppliersByProduct($productId)
// {
//     return Supplier::whereHas('products', function ($q) use ($productId) {
//         $q->where('products.id', $productId);
//     })->select('id','name')->get();
// }



        public function getProductSupplierDetail($productId, $supplierId)
    {
        $product = Product::with(['suppliers' => function ($q) use ($supplierId) {
            $q->where('suppliers.id', $supplierId);
        }])->findOrFail($productId);

        $supplier = $product->suppliers->firstOrFail();

        $pivot = $supplier->pivot;

        return [
            'id'   => $product->id,
            'name' => $product->name,
            'code' => $product->sku_code,
            'unit' => $product->unit_1_name,

            // 🔥 ambil dari pivot
            'price' => $pivot->selling_prices,
        ];
    }


    public function simple($id)
    {
        $job = JobCategory::findOrFail($id);

        return response()->json([
            'id'    => $job->id,
            'kode_group'  => $job->kode_group,
            'nama_group'  => $job->nama_group,
            'name'  => $job->job_name,
            'satuan'=> $job->satuan,
            'harga' => $job->grand_total,
        ]);
    }

}