<?php

namespace App\Http\Controllers;

use App\Models\PropertiDijual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertiDijualController extends Controller
{
    public function index(Request $request)
    {
        $items = PropertiDijual::query()
            ->when($request->q, fn ($q, $s) => $q->where('judul', 'like', "%{$s}%")->orWhere('kota', 'like', "%{$s}%"))
            ->latest()->paginate(15)->withQueryString();

        return view('properti-dijual.index', compact('items'));
    }

    public function create()
    {
        return view('properti-dijual.create', ['item' => new PropertiDijual()]);
    }

    public function store(Request $request)
    {
        $data = $this->handleUploads($request, $this->validated($request));
        PropertiDijual::create($data);

        return redirect()->route('jual.index')->with('success', 'Properti berhasil ditambahkan.');
    }

    public function edit(PropertiDijual $properti)
    {
        return view('properti-dijual.edit', ['item' => $properti]);
    }

    public function update(Request $request, PropertiDijual $properti)
    {
        $data = $this->handleUploads($request, $this->validated($request), $properti);
        $properti->update($data);

        return redirect()->route('jual.index')->with('success', 'Properti berhasil diperbarui.');
    }

    public function destroy(PropertiDijual $properti)
    {
        foreach (array_filter([$properti->foto, $properti->agen_foto, ...($properti->galeri ?? [])]) as $path) {
            Storage::disk('public')->delete($path);
        }
        $properti->delete();

        return redirect()->route('jual.index')->with('success', 'Properti dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'judul'        => 'required|string|max:255',
            'status'       => 'required|in:dijual,disewa,terjual',
            'tipe'         => 'required|in:rumah,tanah,ruko,apartemen',
            'harga'        => 'required|integer|min:0',
            'cicilan'      => 'nullable|string|max:100',
            'kota'         => 'required|string|max:100',
            'lokasi'       => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'kt'           => 'nullable|integer|min:0',
            'km'           => 'nullable|integer|min:0',
            'lt'           => 'nullable|integer|min:0',
            'lb'           => 'nullable|integer|min:0',
            'agen_nama'    => 'required|string|max:100',
            'agen_peran'   => 'required|string|max:100',
            'agen_telepon' => 'nullable|string|max:20',
            'foto'         => 'nullable|image|max:4096',
            'galeri.*'     => 'nullable|image|max:4096',
            'agen_foto'    => 'nullable|image|max:2048',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        unset($data['galeri']);

        return $data;
    }

    private function handleUploads(Request $request, array $data, ?PropertiDijual $old = null): array
    {
        foreach (['foto', 'agen_foto'] as $field) {
            if ($request->hasFile($field)) {
                if ($old?->$field) {
                    Storage::disk('public')->delete($old->$field);
                }
                $data[$field] = $request->file($field)->store('properti', 'public');
            } else {
                unset($data[$field]);
            }
        }

        if ($request->hasFile('galeri')) {
            $galeri = $old?->galeri ?? [];
            foreach ($request->file('galeri') as $file) {
                $galeri[] = $file->store('properti', 'public');
            }
            $data['galeri'] = $galeri;
        }

        return $data;
    }
}