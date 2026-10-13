<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\PropertiDijual;
use App\Support\OgImage;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

        // PERBAIKAN: hanya artikel yang sudah terbit (sebelumnya draft ikut tampil di beranda)
        $articles = (class_exists(Article::class) && Schema::hasTable((new Article)->getTable()))
            ? Article::published()
                ->latest('published_at')
                ->take(12)
                ->get()
            : collect();

        return view('welcome', ['listings' => $listings, 'articles' => $articles]);
    }

    public function show(string $slug)
    {
        $listing = PropertiDijual::published()->with(['fotos', 'employee.user', 'province', 'city', 'district', 'subDistrict', 'postalCode'])->where('slug', $slug)->firstOrFail();

        $this->applySeo($listing, $slug);

        return view('listing.show', compact('listing'));
    }

    /** Judul, deskripsi, canonical, dan gambar share untuk halaman listing. */
    private function applySeo(PropertiDijual $listing, string $slug): void
    {
        // CATATAN: nama kolom di bawah adalah tebakan umum. Cek tabel properti_dijual-mu
        // dan sisakan nama yang benar saja (lihat bagian "Cek nama kolom" di balasan).
        $title = $this->pick($listing, ['judul', 'nama', 'title', 'nama_properti']) ?: 'Properti Dijual';

        $location = collect([
            $listing->district?->name,
            $listing->city?->name,
            $listing->province?->name,
        ])->filter()->implode(', ');

        $rawDesc = (string) $this->pick($listing, ['deskripsi', 'description', 'keterangan']);
        $desc    = trim(preg_replace('/\s+/', ' ', strip_tags(str_replace('<', ' <', $rawDesc))));
        if ($desc === '') {
            $desc = "{$title}" . ($location ? " di {$location}" : '') . '. Hubungi Antosa Architect untuk informasi lengkap.';
        }
        $desc = Str::limit($desc, 160);

        $url = route('listing.show', $slug);

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($desc);
        SEOMeta::setCanonical($url);

        OpenGraph::setTitle($title)->setDescription($desc)->setUrl($url)
            ->setType('website')->setSiteName('Antosa Architect');
        TwitterCard::setTitle($title)->setDescription($desc);
        JsonLd::setType('WebPage')->setTitle($title)->setDescription($desc)->setUrl($url);

        // Foto utama -> gambar share 1200x630 yang ringan (dibuat sekali, lalu dipakai ulang)
        $foto = $listing->fotos->first();
        $raw  = $foto ? $this->pick($foto, ['path', 'foto', 'file', 'gambar', 'url', 'nama_file']) : null;

        if ($raw) {
            $path = ltrim(preg_replace('~^/?storage/~', '', $raw), '/');
            $og   = OgImage::ensure($path);

            if ($og) {
                $imgUrl = asset('storage/' . $og);
                OpenGraph::addImage($imgUrl, ['width' => 1200, 'height' => 630]);
                TwitterCard::setImage($imgUrl);
                JsonLd::addImage($imgUrl);
            }
        }
    }

    /** Ambil nilai teks pertama yang terisi dari daftar nama kolom. */
    private function pick($model, array $keys): ?string
    {
        if (! $model) {
            return null;
        }

        foreach ($keys as $key) {
            $value = $model->getAttribute($key);
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return null;
    }
}