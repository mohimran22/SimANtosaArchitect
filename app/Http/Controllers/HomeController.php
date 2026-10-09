<?php

namespace App\Http\Controllers;

use App\Models\PropertiDijual;
use App\Models\Article;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $listings = null;

        // Pengaman: kalau model/tabel belum ada di server, homepage tetap tampil
        // memakai data contoh (tidak error 500).
        if (class_exists(PropertiDijual::class) && Schema::hasTable('properti_dijual')) {
            $data = PropertiDijual::published()->with(['employee.user', 'province', 'city', 'district'])->withCount('fotos')->latest()->take(9)->get();
            $listings = $data->isNotEmpty() ? $data : null;
        }
        $articles = (class_exists(Article::class) && Schema::hasTable((new Article)->getTable()))
            ? Article::query()
                // ->where('status', 'published')   // sesuaikan dengan kolom/scope publish-mu
                ->latest()                          // atau ->latest('published_at')
                ->take(12)
                ->get()
            : collect();
        return view('welcome', ['listings' => $listings, 'articles' => $articles]);
    }

    public function show(string $slug)
    {
        $listing = PropertiDijual::published()->with(['fotos', 'employee.user', 'province', 'city', 'district', 'subDistrict', 'postalCode'])->where('slug', $slug)->firstOrFail();

        return view('listing.show', compact('listing'));
    }
}