<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Models\JobCategoryItem;
use App\Models\LaborCost;
use App\Models\EquipmentCost;
use App\Models\Product;
use App\Models\ProductSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use DB;

class JobCategoryController extends Controller
{
    public function index()
    {
        $jobs = JobCategory::orderBy('kode_urut')->get();
        return view('job-categories.index', compact('jobs'));
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
        // $products = Product::all();
        // $laborcosts = LaborCost::all();
        // $equipments = EquipmentCost::all();

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

        'code'              => 'nullable|string|max:50',
        'unit'              => 'nullable|string|max:50',
        'name'              => 'nullable|string|max:255',
    ]);

    $data['job_category_id'] = $jobCategory->id;
    $data['total_price']     = $data['coefisien'] * $data['base_unit_price'];

    JobCategoryItem::create($data);

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

    return response()->json([
        'success' => true
    ]);
}


    /**
     * Update item pekerjaan
     */
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

    /**
     * Hapus item pekerjaan
     */
    public function deleteItem(JobCategoryItem $item)
    {
        $item->delete();

        return back()->with('success', 'Item berhasil dihapus.');
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

    'product' => $this->getProductWithSupplierPrice($id),

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
    };
}

protected function getProductWithSupplierPrice($productId)
{
    $product = Product::select(
        'id',
        'name',
        'sku_code as code',
        'unit_1_name as unit'
    )->findOrFail($productId);

    $supplier = ProductSupplier::where('product_id', $productId)
        ->orderBy('selling_prices') // bisa diganti: supplier utama
        ->first();

    return [
        'id'    => $product->id,
        'name'  => $product->name,
        'code'  => $product->code,
        'unit'  => $product->unit,
        'price' => $supplier?->selling_prices ?? 0,
    ];
}
}