<?php

namespace App\Http\Controllers;

use App\Models\PropertiDijual;

class HomeController extends Controller
{
    public function index()
    {
        $listings = PropertiDijual::published()->latest()->take(9)->get();

        // kalau belum ada data, kirim null supaya homepage memakai data contoh
        return view('welcome', ['listings' => $listings->isNotEmpty() ? $listings : null]);
    }

    public function show(string $slug)
    {
        $listing = PropertiDijual::published()->where('slug', $slug)->firstOrFail();

        return view('listing.show', compact('listing'));
    }
}