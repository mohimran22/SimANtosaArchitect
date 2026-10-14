<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;

class LayananController extends Controller
{
    /** /jasa-arsitek, /jasa-renovasi-rumah, /jasa-bangun-rumah */
    public function show(Request $request, string $slug)
    {
        $l = config("layanan.$slug");
        abort_unless($l, 404);

        $hero = $l['hero_image'] ?? null;
        SEOMeta::setTitle($l['meta_title']);
        // pakai data & section homepage yang sama (listing, artikel, klien, dst.), lalu timpa yang beda
        return app(HomeController::class)->index($request)->with([
            'heroImage'       => ($hero && file_exists(public_path($hero))) ? $hero : null,
            'heroTitle'       => $l['hero_title'] ?? null,
            'servicesHeading' => $l['heading'],
            'services'        => $l['items'],
            'showListing'     => false,
        ]);
    }
}