<?php

namespace App\Http\Controllers;

use App\Models\PropertiDijual;
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

        return view('welcome', ['listings' => $listings]);
    }

    public function show(string $slug)
    {
        $listing = PropertiDijual::published()->with(['fotos', 'employee.user', 'province', 'city', 'district', 'subDistrict', 'postalCode'])->where('slug', $slug)->firstOrFail();

        return view('listing.show', compact('listing'));
    }
}