<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCost;
use Illuminate\Http\Request;

class EquipmentCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $tools = EquipmentCost::when($search, function ($query) use ($search) {
                $query->where('description', 'ilike', "%$search%")
                      ->orWhere('code', 'ilike', "%$search%");
            })
            ->orderBy('id', 'asc')
            ->paginate(151);

        return view('tools.index', compact('tools', 'search'));
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

        return redirect()->route('equipment_cost.index')
            ->with('success', 'Labor cost created successfully.');
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

        $equipment_cost->update($request->all());

        return redirect()->route('equipment_cost.index')
            ->with('success', 'Labor cost updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentCost $equipment_cost)
    {
        $equipment_cost->delete();

        return redirect()->route('equipment_cost.index')
            ->with('success', 'Labor cost deleted successfully.');
    }
}
