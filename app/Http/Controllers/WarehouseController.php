<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\PostalCode;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
       public function index(Request $request)
    {
        $auth = auth()->user();

        $query = Warehouse::with(['province', 'city', 'district', 'subDistrict', 'postalCode']);

        if ($auth->can('lihat data gudang') && !$auth->can('lihat daftar gudang')) {
            $query->where('user_id', $auth->id);
        }

        if ($request->ajax()) {
            $warehouses = $query->get();

        return DataTables::of($warehouses)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $url = route('warehouses.show', $row->id);
                    $name = Str::title($row->name ?? '-');
                    return '<a href="'.$url.'">'.e($name).'</a>';
                })
                ->addColumn('action', function ($warehouse) {
                    $buttons = '';
                    if (auth()->user()->can('ubah data karyawan')) {
                        $buttons .= '<a href="' . route('warehouses.edit', $warehouse->id) . '" class="btn btn-icon btn-sm btn-dark me-1" title="Ubah">
                                        <i class="ti ti-edit"></i>
                                    </a>';
                    }
                    if (auth()->user()->can('lihat data karyawan')) {
                        $buttons .= '<a href="' . route('warehouses.show', $warehouse->id) . '" class="btn btn-icon btn-sm btn-dark me-1" title="Lihat">
                                        <i class="ti ti-eye"></i>
                                    </a>';

                    }
                    if (auth()->user()->can('hapus data karyawan')) {
                        $buttons .= '<button data-id="' . $warehouse->id . '" class="btn btn-icon btn-sm btn-dark delete-warehouse" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }
                    return $buttons;
                })
                ->rawColumns(['fullname', 'action', 'contract_letter_file', 'training_certificate'])
                ->make(true);
        }

        return view('warehouses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {
        $user = auth()->user();
        $provinces = Province::all();
        return view('warehouses.create', compact('user', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        warehouse::create([
            'name' => $request->name,
        ]);

        return redirect()->route('warehouses.index')
            ->with('success', 'warehouse berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Warehouse $warehouse)
    // {
    //     return view('warehouses.show', compact('warehouse'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $warehouse->update([
            'name' => $request->name,
        ]);

        return redirect()->route('warehouses.index')
            ->with('success', 'warehouse berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'warehouse berhasil dihapus.');

    }
}
