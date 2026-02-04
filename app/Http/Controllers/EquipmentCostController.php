<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCost;
use App\Models\JobCategoryItem;
use App\Services\RabRecalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EquipmentCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    if ($request->ajax()) {

        $query = EquipmentCost::query()->orderBy('id','asc');

        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('base_unit_price', function ($row) {
                return 'Rp ' . number_format($row->base_unit_price, 0, ',', '.');
            })

            ->addColumn('action', function ($row) {

                $edit = route('equipment_costs.edit', $row->id);
                $delete = route('equipment_costs.destroy', $row->id);

                return "
                    <a href='{$edit}' class='btn btn-dark btn-sm'>Edit</a>
                    <button class='btn btn-dark btn-sm btn-delete'
                        data-url='{$delete}'>
                        Delete
                    </button>
                ";
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    return view('tools.index');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'             => 'nullable|string|max:50',
            'description'      => 'required|string|max:255',
            'unit'             => 'required|string|max:50',
            'base_unit_price'  => 'required|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);

        EquipmentCost::create($request->all());

        return redirect()->route('equipment_costs.index')
            ->with('success', 'Data Peralatan berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentCost $equipment_cost)
    {
        return view('tools.edit', compact('equipment_cost'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentCost $equipment_cost)
    {
        $request->validate([
            'code'             => 'nullable|string|max:50',
            'description'      => 'required|string|max:255',
            'unit'             => 'required|string|max:50',
            'base_unit_price'  => 'required|string',
            'notes'            => 'nullable|string',
        ]);
        DB::transaction(function () use ($request, $equipment_cost) {
        $equipment_cost->update($request->all());
                    JobCategoryItem::where('equipment_cost_id', $equipment_cost->id)->update([
                'base_unit_price' => $equipment_cost->base_unit_price,
                'total_price' => DB::raw('coefisien * ' . $equipment_cost->base_unit_price),
            ]);
        });

        RabRecalculator::recalcByEquipment($equipment_cost->id);

        Cache::put('job_category_last_updated', now()->timestamp);

        return redirect()->route('equipment_costs.index')
            ->with('success', 'Data Peralatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentCost $equipment_cost)
    {
        $equipment_cost->delete();

        return redirect()->route('equipment_costs.index')
            ->with('success', 'Data Peralatan berhasil dihapus.');
    }
}
