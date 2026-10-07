<?php

namespace App\Http\Controllers;

use App\Models\PropertiDijual;
use App\Models\PropertiDijualFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Models\City;
use App\Models\{Province, District, SubDistrict, PostalCode};
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PropertiDijualController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(PropertiDijual::query()->latest())
                ->addIndexColumn()
                ->editColumn('foto', fn ($r) => $r->foto
                    ? '<img src="' . asset('storage/' . $r->foto) . '" width="64" class="rounded" alt="">'
                    : '')
                ->editColumn('judul', fn ($r) => e($r->judul) . '<div class="text-muted small">' . e(ucfirst($r->tipe)) . '</div>')
                ->editColumn('harga', fn ($r) => 'Rp ' . number_format($r->harga, 0, ',', '.'))
                ->editColumn('status', function ($r) {
                    $class = match ($r->status) {
                        'dijual'  => 'bg-success text-white',
                        'disewa'  => 'bg-primary text-white',
                        'terjual' => 'bg-secondary text-white',
                        default   => 'bg-secondary text-white',
                    };

                    return '<span class="badge ' . $class . '">'
                        . e(ucfirst($r->status))
                        . '</span>';
                })
                ->editColumn('is_published', fn ($r) => $r->is_published ? 'Ya' : 'Draft')
                ->addColumn('action', function ($r) {
                    $buttons = '';
                    if (auth()->user()->can('ubah data properti')) {
                        $buttons .= '<a href="' . route('jual.edit', $r->id) . '" class="btn btn-icon btn-sm btn-dark me-1" title="Ubah">
                                        <i class="ti ti-edit"></i>
                                    </a>';
                    }
                    // if (auth()->user()->can('lihat data customer')) {
                    //     $buttons .= '<a href="' . route('jual.show', $r->id) . '" class="btn btn-icon btn-sm btn-dark me-1" title="Lihat">
                    //                     <i class="ti ti-eye"></i>
                    //                 </a>';

                    // }
                    if (auth()->user()->can('hapus data properti')) {
                        $buttons .= '<button data-id="' . $r->id . '" class="btn btn-icon btn-sm btn-dark delete-properties" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }
                    return $buttons;
                })
                ->rawColumns(['foto', 'judul', 'status', 'action'])
                ->make(true);
        }

        return view('properti-dijual.index');
    }

    public function create()
    {
        return view('properti-dijual.create', ['item' => new PropertiDijual()]);
    }

    public function store(Request $request)
    {
        $data = $this->handleUploads($request, $this->validated($request));
        $properti = PropertiDijual::create($data);
        $this->simpanFotoTambahan($request, $properti);

        return redirect()->route('jual.index')->with('success', 'Properti berhasil ditambahkan.');
    }

    public function edit(PropertiDijual $properti)
    {
        return view('properti-dijual.edit', ['item' => $properti->load('fotos')]);
    }

    public function update(Request $request, PropertiDijual $properti)
    {
        $data = $this->handleUploads($request, $this->validated($request), $properti);
        $properti->update($data);
        $this->simpanFotoTambahan($request, $properti);

        return redirect()->route('jual.index', $properti)->with('success', 'Properti berhasil diperbarui.');
    }

    public function destroy(Request $request, PropertiDijual $properti)
    {
        $paths = array_filter([$properti->foto, $properti->agen_foto, ...$properti->fotos()->pluck('path')->all()]);
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
        $properti->delete(); // baris foto ikut terhapus (cascade)
 
        // dipanggil lewat AJAX dari tombol .delete-properties di halaman index
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Properti dihapus.']);
        }
 
        return redirect()->route('jual.index')->with('success', 'Properti dihapus.');
    }
 
    public function destroyFoto(PropertiDijualFoto $foto)
    {
        Storage::disk('public')->delete($foto->path);
        $foto->delete();
 
        return back()->with('success', 'Foto dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'judul'        => 'required|string|max:255',
            'status'       => 'required|in:dijual,disewa,terjual',
            'tipe'         => 'required|in:rumah,tanah,ruko,apartemen',
            'harga'        => 'required|integer|min:0',
            'cicilan'      => 'nullable|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'kt'           => 'nullable|integer|min:0',
            'km'           => 'nullable|integer|min:0',
            'lt'           => 'nullable|integer|min:0',
            'lb'           => 'nullable|integer|min:0',
            'foto'         => 'nullable|image|max:4096',
            'galeri.*'     => 'nullable|image|max:4096',
            'employee_id'  => 'nullable|exists:employees,id',
            'province_id'     => ['required', Rule::exists(Province::class, 'id')],
            'city_id'         => ['required', Rule::exists(City::class, 'id')],
            'district_id'     => ['nullable', Rule::exists(District::class, 'id')],
            'sub_district_id' => ['nullable', Rule::exists(SubDistrict::class, 'id')],
            'postal_code_id'  => ['nullable', Rule::exists(PostalCode::class, 'id')],
            'lokasi'          => 'nullable|string|max:255',
            'jumlah_lantai' => 'nullable|integer|min:1|max:20',
            'sertifikat'    => 'nullable|string|max:30',
            'perabotan'     => 'nullable|string|max:30',
            'fasilitas'     => 'nullable|array',
            'video_url'     => 'nullable|url|max:500',
            'maps_url' => ['nullable', 'url', 'max:500',
                'regex:#^https?://(www\.)?(google\.[a-z.]+/maps|maps\.google\.[a-z.]+|goo\.gl/maps|maps\.app\.goo\.gl)#i'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['kota'] = Str::title(Str::lower(City::find($data['city_id'])?->name ?? ''));
        $data['fasilitas'] = $request->input('fasilitas', []);
        unset($data['galeri']); // foto tambahan disimpan di tabel terpisah

        return $data;
    }

    private function handleUploads(Request $request, array $data, ?PropertiDijual $old = null): array
    {
        foreach (['foto'] as $field) {
            if ($request->hasFile($field)) {
                if ($old?->$field) {
                    Storage::disk('public')->delete($old->$field);
                }
                $data[$field] = $request->file($field)->store('properti', 'public');
            } else {
                unset($data[$field]);
            }
        }

        return $data;
    }

    private function simpanFotoTambahan(Request $request, PropertiDijual $properti): void
    {
        if (! $request->hasFile('galeri')) {
            return;
        }

        $urutan = (int) $properti->fotos()->max('urutan');
        foreach ($request->file('galeri') as $file) {
            $properti->fotos()->create([
                'path'   => $file->store('properti', 'public'),
                'urutan' => ++$urutan,
            ]);
        }
    }
}