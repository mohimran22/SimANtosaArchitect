<?php

namespace App\Http\Controllers;

use App\Models\LaborCost;
use App\Models\JobCategoryItem;
use App\Services\RabRecalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class LaborCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    if ($request->ajax()) {

        $query = LaborCost::query()->orderBy('id','asc');

        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('base_unit_price', function ($row) {
                return 'Rp ' . number_format($row->base_unit_price, 0, ',', '.');
            })

            ->addColumn('action', function ($row) {

                $edit = route('labor_costs.edit', $row->id);
                $delete = route('labor_costs.destroy', $row->id);

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

    return view('labor_costs.index');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('labor_costs.create');
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

        LaborCost::create($request->all());

        return redirect()->route('labor_costs.index')
            ->with('success', 'Labor cost created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaborCost $labor_cost)
    {
        return view('labor_costs.edit', compact('labor_cost'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaborCost $laborCost)
    {
        $request->validate([
            'code'             => 'nullable|string|max:50',
            'description'      => 'required|string|max:255',
            'unit'             => 'required|string|max:50',
            'base_unit_price'  => 'required|string',
            'notes'            => 'nullable|string',
        ]);
        DB::transaction(function () use ($request, $laborCost) {

            $laborCost->update($request->all());
                    JobCategoryItem::where('labor_cost_id', $laborCost->id)
            ->update([
                'base_unit_price' => $request->base_unit_price,
                'total_price' => DB::raw('coefisien * ' . (float) $request->base_unit_price),
            ]);
        });

        RabRecalculator::recalcByLabor($laborCost->id);
        
        Cache::put('job_category_last_updated', now()->timestamp);

        return redirect()->route('labor_costs.index')
            ->with('success', 'Labor cost updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaborCost $laborCost)
    {
        $laborCost->delete();

        return redirect()->route('labor_costs.index')
            ->with('success', 'Labor cost deleted successfully.');
    }
}
