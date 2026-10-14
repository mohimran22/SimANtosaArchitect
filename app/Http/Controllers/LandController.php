<?php

namespace App\Http\Controllers;

use App\Models\PropertiDijual;
use App\Models\TipeProperti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use SEO;

class LandController extends Controller
{
    /** /antosa-land : semua properti yang dipublikasikan, dengan pencarian & filter */
    public function index(Request $request)
    {
        $q = PropertiDijual::published()
            ->with(['employee.user', 'province', 'city', 'district'])
            ->withCount('fotos');

        if ($s = trim((string) $request->query('q'))) {
            $q->where(fn ($w) => $w->where('judul', 'like', "%{$s}%")
                ->orWhere('lokasi', 'like', "%{$s}%")
                ->orWhere('kota', 'like', "%{$s}%"));
        }
        if ($tipe = $request->query('tipe'))     $q->where('tipe', $tipe);
        if ($kota = $request->query('kota'))     $q->where('kota', $kota);
        // toggle Dijual / Disewa (default: dijual)
        // $status = in_array($request->query('status'), ['dijual', 'disewa'], true) ? $request->query('status') : 'dijual';
        // $q->where('status', $status);

        match ($request->query('urut')) {
            'termurah' => $q->orderBy('harga'),
            'termahal' => $q->orderByDesc('harga'),
            default    => $q->latest(),
        };

        $listings = $q->paginate(12)->withQueryString();

        $tipeList = class_exists(TipeProperti::class) && Schema::hasTable('tipe_properti')
            ? TipeProperti::orderBy('nama')->pluck('nama')
            : collect(['rumah', 'tanah', 'ruko', 'apartemen']);
        $kotaList = PropertiDijual::published()->whereNotNull('kota')->distinct()->orderBy('kota')->pluck('kota');

        // "Lokasi populer": 5 kota dengan listing terbanyak
        $kotaPopuler = PropertiDijual::published()->whereNotNull('kota')
            ->selectRaw('kota, count(*) as total')->groupBy('kota')->orderByDesc('total')->limit(5)->pluck('kota');

        SEO::setTitle('Antosa Land - Properti Dijual di Jember' . ($listings->currentPage() > 1 ? ' - Halaman ' . $listings->currentPage() : ''));
        SEO::setDescription('Temukan rumah, tanah, dan properti pilihan langsung dari pemilik dan agen terverifikasi di Jember dan sekitarnya.');
        SEO::setCanonical(route('land.index'));

        return view('listing.index', compact('listings', 'tipeList', 'kotaList', 'kotaPopuler'));
    }
}