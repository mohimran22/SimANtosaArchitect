<?php

namespace App\Http\Controllers;

use App\Models\PropertiDijual;
use App\Models\TipeProperti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class TipePropertiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = TipeProperti::query()
                ->select('tipe_properti.*')
                ->selectSub(function ($q) {
                    $q->from('properti_dijual')
                      ->selectRaw('count(*)')
                      ->whereColumn('properti_dijual.tipe', 'tipe_properti.nama');
                }, 'jumlah_properti')
                ->orderBy('nama');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('nama', fn ($r) => e(ucfirst($r->nama)))
                ->editColumn('jumlah_properti', fn ($r) => (int) $r->jumlah_properti . ' properti')
                ->orderColumn('jumlah_properti', 'jumlah_properti $1')
                ->addColumn('action', function ($r) {
                    // TODO: bungkus dengan auth()->user()->can('...') sesuai permission yang kamu pakai
                    return '<button type="button" class="btn btn-icon btn-sm btn-dark me-1 edit-tipe" title="Ubah"
                                data-id="' . $r->id . '" data-nama="' . e($r->nama) . '">
                                <i class="ti ti-edit"></i>
                            </button>'
                         . '<button type="button" class="btn btn-icon btn-sm btn-dark delete-tipe" title="Hapus"
                                data-id="' . $r->id . '" data-nama="' . e(ucfirst($r->nama)) . '" data-jumlah="' . (int) $r->jumlah_properti . '">
                                <i class="ti ti-trash"></i>
                            </button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('tipe-properti.index');
    }

    public function store(Request $request)
    {
        $this->normalisasi($request);

        $request->validate([
            'nama' => ['required', 'string', 'max:50', Rule::unique('tipe_properti', 'nama')],
        ], $this->pesan());

        TipeProperti::create(['nama' => $request->nama]);

        return response()->json(['status' => 'success', 'message' => 'Tipe properti ditambahkan.']);
    }

    public function update(Request $request, TipeProperti $tipe)
    {
        $this->normalisasi($request);

        $request->validate([
            'nama' => ['required', 'string', 'max:50', Rule::unique('tipe_properti', 'nama')->ignore($tipe->id)],
        ], $this->pesan());

        $lama = $tipe->nama;

        // nama tipe disimpan di tiap properti, jadi ikut diperbarui
        DB::transaction(function () use ($tipe, $lama, $request) {
            $tipe->update(['nama' => $request->nama]);
            PropertiDijual::where('tipe', $lama)->update(['tipe' => $request->nama]);
        });

        return response()->json(['status' => 'success', 'message' => 'Tipe properti diperbarui.']);
    }

    public function destroy(TipeProperti $tipe)
    {
        $dipakai = PropertiDijual::where('tipe', $tipe->nama)->count();

        if ($dipakai > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => "Tipe \"" . ucfirst($tipe->nama) . "\" masih dipakai {$dipakai} properti. Ubah tipe propertinya dulu.",
            ], 422);
        }

        $tipe->delete();

        return response()->json(['status' => 'success', 'message' => 'Tipe properti dihapus.']);
    }

    private function normalisasi(Request $request): void
    {
        $request->merge([
            'nama' => mb_strtolower(trim(preg_replace('/\s+/', ' ', (string) $request->nama))),
        ]);
    }

    private function pesan(): array
    {
        return [
            'nama.required' => 'Nama tipe wajib diisi.',
            'nama.max'      => 'Nama tipe maksimal 50 karakter.',
            'nama.unique'   => 'Tipe tersebut sudah ada.',
        ];
    }
}