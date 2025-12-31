<?php

namespace App\Http\Controllers;

use App\Models\AhspGroup;
use App\Models\Ahsp;
use Illuminate\Http\Request;

class AhspGroupController extends Controller
{

public function index()
{
    $ahsps = Ahsp::with('group')
        ->orderBy('ahsp_group_id')
        ->orderBy('kode')
        ->get();

    return view('ahsp-groups.index', compact('ahsps'));
}


    /**
     * Form create
     */
    public function create()
    {
        return view('ahsp-groups.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'price_meter' => 'required|integer|min:0',
        ]);

        $package = AhspGroup::create($data);

        return redirect()
            ->route('ahsp-groups.edit', $package->id)
            ->with('success', 'Paket berhasil dibuat. Silahkan tambahkan item rincian.');
    }

        public function edit(AhspGroup $ahspGroup)
    {
        $ahspGroup->load('ahsps');
        return view('ahsp-groups.edit', compact('ahspGroup'));
    }

    public function update(Request $request, AhspGroup $AhspGroup)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price_meter' => 'required|integer|min:0',
        ]);

        $AhspGroup->update($data);

        return back()->with('success', 'Paket berhasil diperbarui.');
    }

    /**
     * Hapus paket beserta item-nya
     */
    public function destroy(AhspGroup $AhspGroup)
    {
        $AhspGroup->delete();

        return redirect()
            ->route('ahsp-groups.index')
            ->with('success', 'Paket berhasil dihapus.');
    }

    /**
     * Tambah item pekerjaan
     */
    public function addItem(Request $request, AhspGroup $AhspGroup)
    {
        $data = $request->validate([
            'category' => 'nullable|string|max:255',
            'item_name' => 'required|string|max:255',
            'is_optional' => 'nullable'
        ]);

        $data['is_optional'] = $request->has('is_optional');
        $data['design_package_id'] = $AhspGroup->id;

        Ahsps::create($data);

        return back()->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Update item pekerjaan
     */
    public function updateItem(Request $request, Ahsps $ahsps)
    {
        $data = $request->validate([
            'category' => 'nullable|string|max:255',
            'item_name' => 'required|string|max:255',
            'is_optional' => 'nullable'
        ]);

        $data['is_optional'] = $request->has('is_optional');

        $ahsps->update($data);

        return back()->with('success', 'Item berhasil diperbarui.');
    }

    /**
     * Hapus item pekerjaan
     */
    public function deleteItem(Ahsps $ahsps)
    {
        $ahsps->delete();

        return back()->with('success', 'Item berhasil dihapus.');
    }

    /**
     * API: Ambil paket berikut item-itemnya → untuk autofill form penawaran
     */
    public function getPackage($id)
    {
        $package = AhspGroup::with('items')->findOrFail($id);
        return response()->json($package);
    }
}
