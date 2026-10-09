@extends('layouts.website')
@section('content')
@php
    use Illuminate\Support\Str;

    // Nomor kontak: selalu nomor marketing Antosa (bukan nomor karyawan)
    $wa = preg_replace('/\D/', '', (string) config('antosa.wa_marketing', '6285189523863'));
    $wa = str_starts_with($wa, '0') ? '62' . substr($wa, 1) : $wa;
    $waLink = fn (string $pesan) => 'https://wa.me/' . $wa . '?text=' . urlencode($pesan);

    // Semua foto: foto utama dulu, lalu foto tambahan
    $fotoList = collect();
    if ($listing->foto) { $fotoList->push(asset('storage/' . $listing->foto)); }
    foreach ($listing->fotos as $f) { $fotoList->push(asset('storage/' . $f->path)); }
    if ($fotoList->isEmpty()) { $fotoList->push(asset('images/antosa.png')); }

    $total  = $fotoList->count();
    $mode   = min($total, 5);          // menentukan susunan grid galeri
    $thumbs = $fotoList->slice(1, 4);  // key tetap = indeks asli (untuk lightbox)

    $status  = strtolower($listing->status ?? 'dijual');
    $harga   = (int) $listing->harga;
    $ringkas = $harga >= 1000000000
        ? 'Rp ' . rtrim(rtrim(number_format($harga / 1000000000, 2, ',', '.'), '0'), ',') . ' Miliar'
        : ($harga >= 1000000
            ? 'Rp ' . rtrim(rtrim(number_format($harga / 1000000, 2, ',', '.'), '0'), ',') . ' Juta'
            : 'Rp ' . number_format($harga, 0, ',', '.'));

    $kodeListing = 'AN-' . str_pad((string) $listing->id, 6, '0', STR_PAD_LEFT);
    $diperbarui  = $listing->updated_at
        ? str_replace(' yang lalu', ' lalu', $listing->updated_at->copy()->locale('id')->diffForHumans())
        : null;

    // Ringkasan angka di kartu judul (hanya yang terisi)
    $stats = array_filter([
        ['ti-bed',             $listing->kt, 'Kamar Tidur'],
        ['ti-bath',            $listing->km, 'Kamar Mandi'],
        ['ti-arrows-maximize', $listing->lt ? $listing->lt . 'm²' : null, 'Luas Tanah'],
        ['ti-building',        $listing->lb ? $listing->lb . 'm²' : null, 'Luas Bangunan'],
    ], fn ($s) => filled($s[1]));

    // Kolom opsional: hanya tampil kalau kolomnya ada di tabel & terisi
    $attr = $listing->getAttributes();

    $videoUrl = $attr['video_url'] ?? null;
    $videoUrl = (is_string($videoUrl) && Str::startsWith($videoUrl, ['http://', 'https://'])) ? $videoUrl : null;
    $videoLabel = match (true) {
        $videoUrl && Str::contains($videoUrl, 'tiktok')  => 'Tonton video di TikTok',
        $videoUrl && Str::contains($videoUrl, ['youtube', 'youtu.be']) => 'Tonton video di YouTube',
        default => 'Tonton video',
    };

    // Thumbnail video: kolom video_thumbnail (opsional) > YouTube otomatis > TikTok lewat oEmbed (di-cache)
    $videoThumb = null;
    $thumbCol = $attr['video_thumbnail'] ?? null;
    if (is_string($thumbCol) && Str::startsWith($thumbCol, ['http://', 'https://'])) {
        $videoThumb = $thumbCol;
    } elseif ($videoUrl) {
        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([\w-]{11})~', $videoUrl, $mv)) {
            $videoThumb = 'https://img.youtube.com/vi/' . $mv[1] . '/hqdefault.jpg';
        } elseif (Str::contains($videoUrl, 'tiktok')) {
            $videoThumb = \Illuminate\Support\Facades\Cache::remember('tiktok-thumb-' . md5($videoUrl), now()->addHours(3), function () use ($videoUrl) {
                try {
                    $r = \Illuminate\Support\Facades\Http::timeout(3)->get('https://www.tiktok.com/oembed', ['url' => $videoUrl]);
                    return $r->successful() ? $r->json('thumbnail_url') : null;
                } catch (\Throwable $e) {
                    return null;
                }
            });
        }
    }

    $mapsUrl = $attr['maps_url'] ?? null;
    $mapsUrl = (is_string($mapsUrl) && Str::startsWith($mapsUrl, ['http://', 'https://']))
        ? $mapsUrl
        : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($listing->alamat_lengkap);   // cadangan: cari lewat alamat

    $fas = $attr['fasilitas'] ?? null;
    if (is_string($fas)) {
        $d = json_decode($fas, true);
        $fas = is_array($d) ? $d : preg_split('/\r\n|\r|\n|,/', $fas);
    }
    $fasilitas = collect($fas ?? [])->map(fn ($x) => trim((string) $x))->filter()->values();

    // Ikon fasilitas berdasarkan kata kunci (default: centang)
    $ikonFasilitas = function (string $nama): string {
        $n = Str::lower($nama);
        foreach ([
            'wifi' => 'ti-wifi', 'internet' => 'ti-wifi', 'carport' => 'ti-car', 'garasi' => 'ti-car-garage',
            'sekolah' => 'ti-school', 'rumah sakit' => 'ti-heartbeat', 'ibadah' => 'ti-building-church',
            'banjir' => 'ti-droplet', 'minimarket' => 'ti-building-store', 'parkir' => 'ti-motorbike',
            'jalan raya' => 'ti-map-pin', 'belanja' => 'ti-shopping-cart', 'kolam' => 'ti-swimming',
            'taman' => 'ti-plant', 'keamanan' => 'ti-shield-check', 'security' => 'ti-shield-check',
            'cctv' => 'ti-device-cctv', 'ac' => 'ti-snowflake', 'listrik' => 'ti-bolt', 'air' => 'ti-droplet',
        ] as $kunci => $ikon) {
            if (Str::contains($n, $kunci)) { return $ikon; }
        }
        return 'ti-circle-check';
    };

    // Detail lokasi
    $nm = fn ($rel) => optional($listing->$rel)->name ? Str::title(Str::lower($listing->$rel->name)) : null;
    $wilayah = array_filter([
        'Kecamatan' => $nm('district'),
        'Kota'      => $nm('city'),
        'Provinsi'  => $nm('province'),
    ]);

    // Spesifikasi lengkap (baris yang kosong otomatis tidak tampil)
    $lantai    = $attr['jumlah_lantai'] ?? null;
    $sertifikat = $attr['sertifikat'] ?? null;
    $perabotan  = $attr['perabotan'] ?? null;
    $spesifikasi = array_filter([
        'Tipe properti' => $listing->tipe ? ucfirst($listing->tipe) : null,
        'Tipe iklan'    => ucfirst($listing->status ?? 'dijual'),
        'Kamar Tidur'   => $listing->kt,
        'Kamar Mandi'   => $listing->km,
        'Jumlah lantai' => filled($lantai) ? $lantai . ' lantai' : null,
        'Luas tanah'    => $listing->lt ? $listing->lt . ' m²' : null,
        'Luas bangunan' => $listing->lb ? $listing->lb . ' m²' : null,
        'Sertifikat'    => filled($sertifikat) ? $sertifikat : null,
        'Perabotan'     => filled($perabotan) ? Str::title($perabotan) : null,
        'Kecamatan'     => $nm('district'),
        'Kota'          => $nm('city'),
        'Provinsi'      => $nm('province'),
    ], fn ($v) => filled($v));

    // Agen
    $agenFoto    = $listing->agen_foto ? asset('storage/' . $listing->agen_foto) : null;
    $agenInisial = mb_strtoupper(mb_substr($listing->agen_nama, 0, 1));

    // Jadwal survei: 3 hari cepat (hari ini + 2 hari)
    $hariCepat = collect(range(0, 2))->map(function ($i) use ($listing, $waLink) {
        $d = now()->addDays($i)->locale('id');
        return [
            'hari' => strtoupper($d->isoFormat('ddd')),
            'tgl'  => $d->day,
            'bln'  => $d->isoFormat('MMM'),
            'url'  => $waLink('Halo, saya ingin menjadwalkan survei properti "' . $listing->judul . '" pada ' . $d->isoFormat('dddd, D MMMM YYYY') . '.'),
        ];
    });

    $pesanTanya = 'Halo, saya tertarik dengan listing: ' . $listing->judul;
@endphp

<style>
    .ls{ --ls-top:0px; --ls-red:#e03a2f; --ls-red-dark:#c62f25; --ls-ink:#333; --ls-muted:#8a8f98; --ls-line:#ececf0;
         font-family:'Poppins',sans-serif; color:var(--ls-ink); background:#f8f8fa; padding:26px 0 80px; }
    .ls *{ box-sizing:border-box; }
    .ls a{ color:inherit; }
    .ls-wrap{ width:min(100% - 32px, 1180px); margin:0 auto; }

    .ls-admin{ display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
    .ls-admin-act{ display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .ls-pill{ padding:6px 12px; border-radius:999px; font-size:13px; font-weight:600; background:#eceef1; color:#555; }
    .ls-pill.on{ background:#e6f7ee; color:#128a4a; }
    .ls-abtn{ display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border:1px solid var(--ls-line); border-radius:10px;
              background:#fff; color:var(--ls-ink); font-size:14px; font-weight:600; text-decoration:none; }
    .ls-abtn:hover{ background:#fafafb; border-color:#d3d5db; }
    .ls-crumb{ display:flex; flex-wrap:wrap; gap:8px; font-size:14px; color:var(--ls-muted); margin-bottom:16px; }
    .ls-crumb a{ text-decoration:none; }
    .ls-crumb a:hover{ color:var(--ls-ink); }
    .ls-crumb span:last-child{ color:var(--ls-ink); }

    /* ===== Galeri ===== */
    .ls-galwrap{ position:relative; }
    .ls-gallery{ display:grid; gap:10px; border-radius:20px; overflow:hidden; aspect-ratio:2.71 / 1; }
    .ls-gallery button{ all:unset; position:relative; display:block; cursor:pointer; overflow:hidden; background:#eceef1; }
    .ls-gallery img{ width:100%; height:100%; object-fit:cover; display:block; transition:transform .25s; }
    .ls-gallery button:hover img{ transform:scale(1.03); }
    .ls-gallery button:focus-visible{ outline:3px solid var(--ls-red); outline-offset:-3px; }
    .ls-main{ grid-column:1; grid-row:1 / span 2; }
    .ls-g1{ grid-template-columns:1fr; grid-template-rows:1fr; }
    .ls-g1 .ls-main{ grid-row:1; }
    .ls-g2{ grid-template-columns:2fr 1fr; grid-template-rows:1fr 1fr; }
    .ls-g2 .ls-t{ grid-row:span 2; }
    .ls-g3{ grid-template-columns:2fr 1fr; grid-template-rows:1fr 1fr; }
    .ls-g4{ grid-template-columns:2fr 1fr 1fr; grid-template-rows:1fr 1fr; }
    .ls-g4 .ls-t1{ grid-row:span 2; }
    .ls-g5{ grid-template-columns:2fr 1fr 1fr; grid-template-rows:1fr 1fr; }
    .ls-more{ position:absolute; inset:0; display:flex; align-items:center; justify-content:center; gap:10px;
              background:rgba(0,0,0,.42); color:#fff; font-weight:600; font-size:18px; text-align:center; padding:8px; }
    .ls-more i{ font-size:26px; }
    .ls-allbtn{ display:none; position:absolute; right:12px; bottom:12px; z-index:2; background:rgba(0,0,0,.72); color:#fff;
                padding:8px 14px; border-radius:999px; font-size:13px; font-weight:600; align-items:center; gap:6px; }

    /* Tombol favorit & bagikan melayang di pojok kanan atas galeri */
    .ls-gact{ position:absolute; top:15px; right:15px; z-index:3; display:flex; gap:8px; }
    .ls-gact button{ all:unset; box-sizing:border-box; cursor:pointer; width:44px; height:44px; border-radius:50%; background:#fff; color:#333;
                     display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 1px 5px rgba(0,0,0,.18); }
    .ls-gact button:hover{ color:var(--ls-red); }
    .ls-gact button.on{ color:var(--ls-red); }
    .ls-gact button:focus-visible{ outline:2px solid var(--ls-red); outline-offset:2px; }

    /* ===== Tab ===== */
    .ls-tabs{ position:sticky; top:var(--ls-top); z-index:20; display:flex; align-items:stretch; background:#fff;
              border-bottom:1px solid var(--ls-line); margin-top:12px; overflow-x:auto; scrollbar-width:none; }
    .ls-tabs::-webkit-scrollbar{ display:none; }
    .ls-tabs a.t{ flex:none; padding:17px 16px; font-size:15px; font-weight:500; color:#555; text-decoration:none;
                  border-bottom:3px solid transparent; margin-bottom:-1px; }
    .ls-tabs a.t:hover{ color:var(--ls-ink); }
    .ls-tabs a.t.active{ color:var(--ls-ink); font-weight:600; border-bottom-color:var(--ls-red); }
    .ls-tabs a.t:focus-visible, .ls-tabs button:focus-visible{ outline:2px solid var(--ls-red); outline-offset:-2px; }
    .ls-tab-actions{ margin-left:auto; display:flex; flex:none; border-left:1px solid var(--ls-line); }
    .ls-tab-actions button{ all:unset; cursor:pointer; display:flex; align-items:center; gap:8px; padding:0 18px; font-size:15px; font-weight:500; color:#444; }
    .ls-tab-actions button:hover{ color:var(--ls-red); }
    .ls-tab-actions button.on{ color:var(--ls-red); }
    .ls-tab-actions i{ font-size:20px; }

    /* ===== Isi ===== */
    .ls-body{ display:grid; grid-template-columns:minmax(0,1fr) 452px; gap:30px; align-items:start; margin-top:26px; }
    .ls-card{ background:#fff; border:1px solid var(--ls-line); border-radius:18px; padding:30px; margin-bottom:26px; scroll-margin-top:calc(var(--ls-top) + 70px); }
    .ls-card h2{ font-size:22px; font-weight:700; margin:0 0 6px; color:#2b2b2b; display:flex; align-items:center; gap:10px; }
    .ls-card h2 i{ color:var(--ls-red); font-size:26px; }
    .ls-sub{ color:var(--ls-muted); font-size:15px; margin:0 0 18px; }

    .ls-top{ display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap; }
    .ls-badges{ display:flex; gap:10px; flex-wrap:wrap; }
    .ls-badge{ display:inline-flex; align-items:center; gap:6px; padding:9px 13px; border-radius:6px; font-size:13px; font-weight:700; background:var(--ls-red); color:#fff; letter-spacing:.2px; }
    .ls-badge.alt{ background:#fff; color:#444; border:1px solid var(--ls-line); font-weight:600; }
    .ls-ids{ text-align:right; font-size:13px; color:var(--ls-muted); line-height:1.8; }
    .ls-ids i{ margin-right:4px; }
    .ls-title{ font-size:clamp(23px, 3vw, 31px); line-height:1.3; margin:18px 0 8px; font-weight:700; color:#3a3a3a; }
    .ls-loc{ display:flex; gap:8px; align-items:flex-start; color:#666; font-size:16px; margin:0; }
    .ls-loc i{ font-size:20px; margin-top:2px; }
    .ls-price{ font-size:clamp(30px, 5vw, 42px); font-weight:800; color:var(--ls-red); margin:18px 0 0; line-height:1.15; }
    .ls-cicil{ font-size:14px; color:var(--ls-muted); margin:6px 0 0; }
    .ls-stats{ display:grid; grid-template-columns:repeat(auto-fit, minmax(110px,1fr)); gap:14px; text-align:center;
               border-top:1px solid var(--ls-line); margin-top:26px; padding-top:26px; }
    .ls-stats i{ font-size:28px; color:var(--ls-red); }
    .ls-stats b{ display:block; font-size:20px; font-weight:600; margin-top:6px; color:#333; }
    .ls-stats span{ display:block; font-size:14px; color:var(--ls-muted); }

    .ls-btn-out{ display:inline-flex; align-items:center; gap:10px; padding:13px 20px; border:1.5px solid var(--ls-red); color:var(--ls-red) !important;
                 border-radius:10px; font-weight:600; font-size:16px; text-decoration:none; background:#fff; }
    .ls-btn-out:hover{ background:#fff5f4; }
    .ls-fas{ display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:16px 20px; list-style:none; margin:0; padding:0; }
    .ls-fas li{ display:flex; gap:12px; align-items:center; font-size:16px; color:#333; }
    .ls-fas i{ color:var(--ls-red); font-size:22px; flex:none; }
    .ls-spec{ display:grid; grid-template-columns:1fr 1fr; column-gap:40px; margin:0; }
    .ls-spec div{ display:flex; justify-content:space-between; gap:16px; padding:15px 0; border-bottom:1px solid var(--ls-line); font-size:16px; }
    .ls-spec dt{ color:var(--ls-muted); font-weight:400; }
    .ls-spec dd{ margin:0; font-weight:500; color:#2b2b2b; text-align:right; }
    .ls-desc{ white-space:pre-line; line-height:1.8; font-size:16px; margin:0; color:#444; }
    .ls-desc-wrap{ position:relative; }
    .ls-desc-wrap.is-clamp{ max-height:212px; overflow:hidden;
        -webkit-mask-image:linear-gradient(#000 62%, transparent 100%); mask-image:linear-gradient(#000 62%, transparent 100%); }
    .ls-desc-wrap.is-clamp.open{ max-height:none; -webkit-mask-image:none; mask-image:none; }
    .ls-more-btn{ all:unset; box-sizing:border-box; cursor:pointer; display:inline-flex; align-items:center; gap:10px; margin-top:14px;
                  padding:12px 18px; border:1px solid var(--ls-line); border-radius:10px; background:#fff; font-size:16px; font-weight:600; color:#2b2b2b; }
    .ls-more-btn[hidden]{ display:none; }
    .ls-more-btn:hover{ border-color:#d3d5db; background:#fafafb; }
    .ls-more-btn:focus-visible{ outline:2px solid var(--ls-red); outline-offset:2px; }
    .ls-more-btn i{ font-size:18px; transition:transform .2s; }
    .ls-more-btn[aria-expanded="true"] i{ transform:rotate(180deg); }
    .ls-dl{ display:grid; grid-template-columns:repeat(auto-fill, minmax(190px,1fr)); gap:14px 20px; margin:18px 0 0; }
    .ls-dl div span{ display:block; font-size:13px; color:var(--ls-muted); }
    .ls-dl div b{ font-weight:600; font-size:15px; }
    .ls-info{ margin:18px 0 10px; font-size:15px; color:#666; line-height:1.7; }
    .ls-info-btn{ all:unset; box-sizing:border-box; cursor:default; display:inline-flex; align-items:center; gap:10px;
                  padding:12px 20px; border-radius:10px; background:#12c25a; color:#fff; font-size:15px; font-weight:600; }
    .ls-info-btn i{ font-size:22px; }
    .ls-info-btn:focus-visible{ outline:2px solid var(--ls-red); outline-offset:2px; }
    .ls-vid{ position:relative; display:block; aspect-ratio:16 / 9; border-radius:14px; overflow:hidden; text-decoration:none;
             background:linear-gradient(135deg,#2b2d33,#111214); }
    .ls-vid img{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; transition:transform .25s; }
    .ls-vid:hover img{ transform:scale(1.03); }
    .ls-vid-play{ position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:68px; height:68px; border-radius:50%;
                  background:rgba(0,0,0,.55); border:2px solid #fff; color:#fff; display:flex; align-items:center; justify-content:center; font-size:30px; }
    .ls-vid:hover .ls-vid-play{ background:var(--ls-red); border-color:var(--ls-red); }
    .ls-vid-cap{ position:absolute; left:0; right:0; bottom:0; padding:28px 16px 12px; color:#fff; font-size:14px; font-weight:600;
                 display:flex; align-items:center; gap:8px; background:linear-gradient(transparent, rgba(0,0,0,.7)); }
    .ls-vid:focus-visible{ outline:3px solid var(--ls-red); outline-offset:2px; }
    .ls-maplink{ display:inline-flex; gap:8px; align-items:center; margin-top:20px; font-size:15px; font-weight:600; color:var(--ls-red) !important; text-decoration:none; }

    /* ===== Sidebar ===== */
    .ls-side{ position:sticky; top:calc(var(--ls-top) + 76px); }
    .ls-side .ls-card{ padding:30px; margin-bottom:20px; }
    .ls-agent{ display:flex; gap:16px; align-items:center; margin-bottom:22px; }
    .ls-avatar{ width:56px; height:56px; border-radius:50%; object-fit:cover; background:#222; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:22px; flex:none; }
    .ls-agent strong{ display:block; font-size:18px; font-weight:600; color:#2b2b2b; }
    .ls-agent small{ color:#666; font-size:14px; display:flex; align-items:center; gap:6px; }
    .ls-agent small::before{ content:""; width:7px; height:7px; border-radius:50%; background:#f5a623; }
    .ls-cta{ display:flex; align-items:center; justify-content:center; gap:10px; width:100%; height:64px; border-radius:14px; font-weight:600; font-size:19px; text-decoration:none; color:#fff !important; }
    .ls-cta i{ font-size:24px; }
    .ls-cta.wa{ background:#12c25a; }
    .ls-cta.wa:hover{ background:#0fa94d; }
    .ls-cta.tel{ background:var(--ls-red); margin-top:12px; }
    .ls-cta.tel:hover{ background:var(--ls-red-dark); }
    .ls-survey{ margin-top:26px; }
    .ls-survey-h{ display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
    .ls-survey-h strong{ font-size:17px; font-weight:600; display:flex; align-items:center; gap:8px; }
    .ls-survey-h strong i{ color:var(--ls-red); font-size:22px; }
    .ls-survey-h small{ font-size:12px; letter-spacing:.6px; color:var(--ls-muted); font-weight:600; }
    .ls-days{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
    .ls-day{ display:flex; flex-direction:column; align-items:center; padding:12px 6px; border:1px solid var(--ls-line); border-radius:14px; text-decoration:none; line-height:1.25; }
    .ls-day:hover{ border-color:var(--ls-red); }
    .ls-day span{ font-size:12px; color:var(--ls-muted); font-weight:600; }
    .ls-day b{ font-size:24px; font-weight:700; color:#2b2b2b; }
    .ls-day small{ font-size:13px; color:var(--ls-muted); }
    .ls-other{ all:unset; box-sizing:border-box; cursor:pointer; width:100%; margin-top:14px; display:flex; align-items:center; justify-content:center; gap:10px;
               padding:15px; border:1.5px dashed #d8dae0; border-radius:12px; font-size:16px; color:#444; }
    .ls-other:hover{ border-color:var(--ls-red); color:var(--ls-red); }
    .ls-other:focus-visible{ outline:2px solid var(--ls-red); outline-offset:2px; }
    .ls-custom{ margin-top:14px; display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .ls-custom[hidden]{ display:none; }
    .ls-custom input{ width:100%; padding:11px 12px; border:1px solid var(--ls-line); border-radius:10px; font:inherit; font-size:15px; }
    .ls-custom button{ grid-column:1 / -1; padding:13px; border:0; border-radius:10px; background:#12c25a; color:#fff; font:inherit; font-weight:600; font-size:15px; cursor:pointer; }

    .ls-acc{ border-radius:16px; margin-bottom:14px; background:#fff; border:1px solid var(--ls-line); }
    .ls-acc.panduan{ background:#fffaea; border-color:#fbe7a6; }
    .ls-acc summary{ list-style:none; cursor:pointer; display:flex; align-items:center; gap:12px; padding:18px 20px; font-size:17px; font-weight:500; }
    .ls-acc summary::-webkit-details-marker{ display:none; }
    .ls-acc summary:focus-visible{ outline:2px solid var(--ls-red); outline-offset:-2px; border-radius:16px; }
    .ls-acc summary > i:first-child{ font-size:24px; color:#f5a623; }
    .ls-acc.disc summary > i:first-child{ color:#9aa0aa; }
    .ls-acc.disc summary{ color:#555; }
    .ls-acc .chev{ margin-left:auto; width:34px; height:34px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; font-size:18px; color:#666; transition:transform .2s; }
    .ls-acc.disc .chev{ background:none; }
    .ls-acc[open] .chev{ transform:rotate(180deg); }
    .ls-acc-body{ padding:0 22px 20px; font-size:14.5px; line-height:1.75; color:#555; }
    .ls-acc-body ol{ margin:0; padding-left:20px; }
    .ls-acc-body li{ margin-bottom:6px; }
    .ls-report{ display:flex; justify-content:space-between; gap:10px; font-size:14.5px; color:#777; padding:2px 4px; }
    .ls-report a{ color:#333; font-weight:600; display:inline-flex; gap:6px; align-items:center; }

    /* Bar kontak mobile */
    .ls-bar{ display:none; }

    /* ===== Lightbox ===== */
    .ls-lb{ position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.92); display:flex; align-items:center; justify-content:center; }
    .ls-lb[hidden]{ display:none; }
    .ls-lb img{ max-width:min(94vw,1200px); max-height:84vh; object-fit:contain; border-radius:6px; }
    .ls-lb button{ all:unset; cursor:pointer; position:absolute; color:#fff; width:46px; height:46px; display:flex; align-items:center; justify-content:center; border-radius:50%; background:rgba(255,255,255,.14); font-size:22px; }
    .ls-lb button:hover{ background:rgba(255,255,255,.28); }
    .ls-lb button:focus-visible{ outline:2px solid #fff; }
    .ls-lb .x{ top:18px; right:18px; }
    .ls-lb .prev{ left:18px; top:50%; transform:translateY(-50%); }
    .ls-lb .next{ right:18px; top:50%; transform:translateY(-50%); }
    .ls-lb .count{ position:absolute; bottom:18px; left:50%; transform:translateX(-50%); color:#fff; font-size:14px; }

    @media (max-width:1100px){ .ls-body{ grid-template-columns:minmax(0,1fr) 380px; } }
    @media (max-width:900px){
        .ls-body{ grid-template-columns:1fr; }
        .ls-side{ position:static; }
        .ls-bar{ display:flex; gap:10px; position:fixed; left:0; right:0; bottom:0; z-index:30; background:#fff; padding:10px 14px; border-top:1px solid var(--ls-line); }
        .ls-bar a{ flex:1; display:flex; align-items:center; justify-content:center; gap:8px; height:48px; border-radius:12px; color:#fff !important; font-weight:600; text-decoration:none; }
        .ls-bar .wa{ background:#12c25a; } .ls-bar .tel{ background:var(--ls-red); }
        .ls{ padding-bottom:100px; }
    }
    @media (max-width:640px){
        .ls-gallery{ display:block; aspect-ratio:auto; height:clamp(220px, 62vw, 340px); position:relative; border-radius:14px; }
        .ls-gallery .ls-t{ display:none; }
        .ls-gallery .ls-main{ width:100%; height:100%; }
        .ls-allbtn{ display:inline-flex; }
        .ls-card, .ls-side .ls-card{ padding:20px; border-radius:14px; }
        .ls-tab-actions button span{ display:none; }
        .ls-tab-actions button{ padding:0 14px; }
        .ls-ids{ text-align:left; }
        .ls-spec{ grid-template-columns:1fr; }
        .ls-fas{ grid-template-columns:1fr 1fr; }
        .ls-cta{ height:56px; font-size:17px; }
    }
    @media (prefers-reduced-motion:reduce){ .ls-gallery img, .ls-acc .chev{ transition:none; } }
</style>

<section class="ls">
    <div class="ls-wrap">
        {{-- Bar admin: hanya untuk user login --}}
        <div class="ls-admin">
            <nav class="ls-crumb" aria-label="Breadcrumb" style="margin:0">
                <a href="{{ route('jual.index') }}">Properti Dijual</a><span>›</span>
                <span>{{ $listing->judul }}</span>
            </nav>
            <div class="ls-admin-act">
                <span class="ls-pill {{ $listing->is_published ? 'on' : '' }}">{{ $listing->is_published ? 'Terbit di website' : 'Draft' }}</span>
                @can('ubah data properti')
                    <a href="{{ route('jual.edit', $listing->id) }}" class="ls-abtn"><i class="ti ti-edit"></i> Ubah</a>
                @endcan
                @if ($listing->is_published && Route::has('listing.show'))
                    <a href="{{ $listing->url }}" target="_blank" rel="noopener" class="ls-abtn"><i class="ti ti-external-link"></i> Lihat di website</a>
                @endif
                <a href="{{ route('jual.index') }}" class="ls-abtn"><i class="ti ti-arrow-left"></i> Kembali</a>
            </div>
        </div>

        {{-- ===== Galeri ===== --}}
        <div id="foto" style="scroll-margin-top:calc(var(--ls-top) + 16px)">
            <div class="ls-galwrap">
                <div class="ls-gallery ls-g{{ $mode }}">
                    <button type="button" class="ls-main" data-open="0" aria-label="Buka foto utama">
                        <img src="{{ $fotoList[0] }}" alt="{{ $listing->judul }}">
                        @if ($total > 1)
                            <span class="ls-allbtn"><i class="ti ti-library-photo"></i> Lihat semua {{ $total }} foto</span>
                        @endif
                    </button>
                    @foreach ($thumbs as $i => $src)
                        <button type="button" class="ls-t ls-t{{ $loop->iteration }}" data-open="{{ $i }}" aria-label="Buka foto {{ $i + 1 }}">
                            <img src="{{ $src }}" alt="" loading="lazy">
                            @if ($loop->last && $total > 5)
                                <span class="ls-more"><i class="ti ti-library-photo"></i> Lihat semua {{ $total }} foto</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                <div class="ls-gact">
                    <button type="button" data-fav aria-pressed="false" aria-label="Simpan ke favorit"><i class="ti ti-heart"></i></button>
                    <button type="button" data-share aria-label="Bagikan"><i class="ti ti-share"></i></button>
                </div>
            </div>
        </div>

        {{-- ===== Tab ===== --}}
        <nav class="ls-tabs" id="lsTabs" aria-label="Bagian halaman">
            <a class="t active" href="#foto">Foto</a>
            <a class="t" href="#detail">Detail Properti</a>
            @if ($fasilitas->isNotEmpty())<a class="t" href="#fasilitas">Fasilitas</a>@endif
            @if (filled($listing->deskripsi))<a class="t" href="#deskripsi">Deskripsi</a>@endif
            <a class="t" href="#lokasi">Detail Lokasi</a>
            <div class="ls-tab-actions">
                <button type="button" id="lsFav" data-fav aria-pressed="false"><i class="ti ti-heart"></i><span>Favorit</span></button>
                <button type="button" id="lsShare" data-share><i class="ti ti-share"></i><span id="lsShareTxt">Bagikan</span></button>
            </div>
        </nav>

        <div class="ls-body">
            <div>
                {{-- Kartu judul --}}
                <div class="ls-card" id="detail">
                    <div class="ls-top">
                        <div class="ls-badges">
                            <span class="ls-badge">{{ strtoupper($listing->status ?? 'Dijual') }}</span>
                            @if ($listing->tipe)<span class="ls-badge alt"><i class="ti ti-home"></i> {{ strtoupper($listing->tipe) }}</span>@endif
                        </div>
                        <div class="ls-ids">
                            <div>ID: {{ $kodeListing }}</div>
                            @if ($diperbarui)<div><i class="ti ti-clock"></i>Diperbarui {{ $diperbarui }}</div>@endif
                        </div>
                    </div>

                    <h1 class="ls-title">{{ $listing->judul }}</h1>
                    <p class="ls-loc"><i class="ti ti-map-pin"></i> <span>{{ $listing->lokasi_lengkap }}</span></p>

                    <div class="ls-price" title="Rp {{ number_format($harga, 0, ',', '.') }}">{{ $ringkas }}</div>
                    @if ($listing->cicilan)<p class="ls-cicil">Cicilan mulai {{ $listing->cicilan }}</p>@endif

                    @if ($stats)
                        <div class="ls-stats">
                            @foreach ($stats as [$ikon, $nilai, $label])
                                <div><i class="ti {{ $ikon }}"></i><b>{{ $nilai }}</b><span>{{ $label }}</span></div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Video (opsional: kolom video_url) --}}
                @if ($videoUrl)
                    <div class="ls-card">
                        <h2><i class="ti ti-player-play"></i> Video Properti</h2>
                        <p class="ls-sub">Tur singkat properti dalam format video.</p>
                        <a class="ls-vid" href="{{ $videoUrl }}" target="_blank" rel="noopener" aria-label="{{ $videoLabel }}">
                            @if ($videoThumb)
                                <img src="{{ $videoThumb }}" alt="Thumbnail video {{ $listing->judul }}" loading="lazy" referrerpolicy="no-referrer"
                                     onerror="this.remove()">
                            @endif
                            <span class="ls-vid-play"><i class="ti ti-player-play-filled"></i></span>
                            <span class="ls-vid-cap">{{ $videoLabel }} <i class="ti ti-external-link"></i></span>
                        </a>
                    </div>
                @endif

                {{-- Tentang listing --}}
                @if (filled($listing->deskripsi))
                    <div class="ls-card" id="deskripsi">
                        <h2 style="margin-bottom:16px">Tentang Listing Ini</h2>
                        <div class="ls-desc-wrap" id="lsDescWrap">
                            <p class="ls-desc" id="lsDesc">{{ $listing->deskripsi }}</p>
                        </div>
                        <button type="button" class="ls-more-btn" id="lsDescBtn" aria-expanded="false" aria-controls="lsDesc" hidden>
                            <span>Baca selengkapnya</span> <i class="ti ti-chevron-down"></i>
                        </button>
                    </div>
                @endif

                {{-- Spesifikasi lengkap --}}
                @if ($spesifikasi)
                    <div class="ls-card" id="spesifikasi">
                        <h2 style="margin-bottom:12px">Spesifikasi Lengkap</h2>
                        <dl class="ls-spec">
                            @foreach ($spesifikasi as $label => $nilai)
                                <div><dt>{{ $label }}</dt><dd>{{ $nilai }}</dd></div>
                            @endforeach
                        </dl>
                    </div>
                @endif

                {{-- Fasilitas (opsional: kolom fasilitas) --}}
                @if ($fasilitas->isNotEmpty())
                    <div class="ls-card" id="fasilitas">
                        <h2 style="margin-bottom:18px">Fasilitas yang Tersedia</h2>
                        <ul class="ls-fas">
                            @foreach ($fasilitas as $x)
                                <li><i class="ti {{ $ikonFasilitas($x) }}"></i> {{ $x }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Detail lokasi --}}
                <div class="ls-card" id="lokasi">
                    <h2 style="margin-bottom:12px">Detail Lokasi</h2>
                    @if ($wilayah)
                        <div class="ls-dl">
                            @foreach ($wilayah as $label => $nilai)
                                <div><span>{{ $label }}</span><b>{{ $nilai }}</b></div>
                            @endforeach
                        </div>
                    @endif
                    <p class="ls-info">Info lebih lanjut dapat menghubungi</p>
                    <button type="button" class="ls-info-btn">
                        <i class="ti ti-brand-whatsapp"></i> Whatsapp Antosa Land
                    </button>
                    {{-- <a class="ls-maplink" target="_blank" rel="noopener"
                       href="{{ $mapsUrl }}">
                        <i class="ti ti-map-2"></i> Lihat di Google Maps
                    </a> --}}
                </div>

            </div>

            {{-- ===== Sidebar ===== --}}
            <aside class="ls-side">
                <div class="ls-card">
                    <div class="ls-agent">
                        @if ($agenFoto)
                            <img class="ls-avatar" src="{{ $agenFoto }}" alt="{{ $listing->agen_nama }}">
                        @else
                            <span class="ls-avatar" aria-hidden="true">{{ $agenInisial }}</span>
                        @endif
                        <div>
                            <strong>{{ $listing->agen_nama }}</strong>
                            <small>{{ $listing->agen_peran }}</small>
                        </div>
                    </div>

                    <a class="ls-cta wa" target="_blank" rel="noopener" href="{{ $waLink($pesanTanya) }}">
                        <i class="ti ti-brand-whatsapp"></i> Tanya via WhatsApp
                    </a>
                    <a class="ls-cta tel" href="tel:+{{ $wa }}"><i class="ti ti-phone"></i> Telepon Langsung</a>

                    <div class="ls-survey">
                        <div class="ls-survey-h">
                            <strong><i class="ti ti-calendar-event"></i> Jadwalkan Survei</strong>
                            <small>PILIH CEPAT</small>
                        </div>
                        <div class="ls-days">
                            @foreach ($hariCepat as $h)
                                <a class="ls-day" href="{{ $h['url'] }}" target="_blank" rel="noopener">
                                    <span>{{ $h['hari'] }}</span><b>{{ $h['tgl'] }}</b><small>{{ $h['bln'] }}</small>
                                </a>
                            @endforeach
                        </div>
                        <button type="button" class="ls-other" id="lsOther" aria-expanded="false" aria-controls="lsCustom">
                            <i class="ti ti-calendar"></i> Pilih tanggal &amp; waktu lain
                        </button>
                        <div class="ls-custom" id="lsCustom" hidden>
                            <input type="date" id="lsTgl" min="{{ now()->format('Y-m-d') }}" aria-label="Tanggal survei">
                            <input type="time" id="lsJam" value="10:00" aria-label="Waktu survei">
                            <button type="button" id="lsKirim">Kirim jadwal via WhatsApp</button>
                        </div>
                    </div>
                </div>

                <details class="ls-acc panduan">
                    <summary><i class="ti ti-sparkles"></i> Panduan Membeli Properti <span class="chev"><i class="ti ti-chevron-down"></i></span></summary>
                    <div class="ls-acc-body">
                        <ol>
                            <li>Pastikan legalitas: jenis sertifikat (SHM/HGB), IMB/PBG, dan PBB terbaru atas nama penjual.</li>
                            <li>Survei langsung, cek kondisi bangunan, akses jalan, dan lingkungan sekitar.</li>
                            <li>Hitung kemampuan cicilan dan biaya tambahan seperti pajak, notaris, dan balik nama.</li>
                            <li>Lakukan transaksi dan penandatanganan akta di hadapan PPAT.</li>
                        </ol>
                    </div>
                </details>

                <details class="ls-acc disc">
                    <summary><i class="ti ti-info-circle"></i> Disclaimer <span class="chev"><i class="ti ti-chevron-down"></i></span></summary>
                    <div class="ls-acc-body">
                        Informasi listing berasal dari pemilik atau agen. Harga, luas, dan ketersediaan dapat berubah sewaktu-waktu. Mohon cek ulang data dan legalitas properti sebelum bertransaksi.
                    </div>
                </details>

                <div class="ls-report">
                    <span>Ada masalah dengan properti ini?</span>
                    <a href="{{ $waLink('Halo, saya ingin melaporkan masalah pada listing ' . $kodeListing . ': ' . $listing->judul) }}" target="_blank" rel="noopener">
                        <i class="ti ti-flag"></i> <u>Laporkan</u>
                    </a>
                </div>
            </aside>
        </div>
    </div>

    {{-- Bar kontak (mobile) --}}
    {{-- <div class="ls-bar">
        <a class="wa" target="_blank" rel="noopener" href="{{ $waLink($pesanTanya) }}"><i class="ti ti-brand-whatsapp"></i> WhatsApp</a>
        <a class="tel" href="tel:+{{ $wa }}"><i class="ti ti-phone"></i> Telepon</a>
    </div> --}}

    {{-- ===== Lightbox ===== --}}
    <div class="ls-lb" id="lsLb" hidden role="dialog" aria-modal="true" aria-label="Galeri foto">
        <button type="button" class="x" data-lb="close" aria-label="Tutup">&times;</button>
        <button type="button" class="prev" data-lb="prev" aria-label="Foto sebelumnya"><i class="ti ti-chevron-left"></i></button>
        <img id="lsLbImg" alt="">
        <button type="button" class="next" data-lb="next" aria-label="Foto berikutnya"><i class="ti ti-chevron-right"></i></button>
        <div class="count" id="lsLbCount"></div>
    </div>
</section>

<script>
(function () {
    // ===== Lightbox =====
    var fotos = @json($fotoList->values());
    var lb = document.getElementById('lsLb');
    var img = document.getElementById('lsLbImg');
    var count = document.getElementById('lsLbCount');
    var idx = 0, pemicu = null;

    function tampil(i) {
        idx = (i + fotos.length) % fotos.length;
        img.src = fotos[idx];
        count.textContent = (idx + 1) + ' / ' + fotos.length;
    }
    function buka(i, el) {
        pemicu = el; tampil(i); lb.hidden = false;
        document.body.style.overflow = 'hidden';
        lb.querySelector('.x').focus();
    }
    function tutup() {
        lb.hidden = true; document.body.style.overflow = '';
        if (pemicu) pemicu.focus();
    }
    document.querySelectorAll('[data-open]').forEach(function (el) {
        el.addEventListener('click', function () { buka(parseInt(el.dataset.open, 10), el); });
    });
    lb.addEventListener('click', function (e) {
        var a = e.target.closest('[data-lb]');
        if (a) { var k = a.dataset.lb; if (k === 'close') tutup(); else tampil(idx + (k === 'next' ? 1 : -1)); }
        else if (e.target === lb) tutup();
    });
    document.addEventListener('keydown', function (e) {
        if (lb.hidden) return;
        if (e.key === 'Escape') tutup();
        else if (e.key === 'ArrowRight') tampil(idx + 1);
        else if (e.key === 'ArrowLeft') tampil(idx - 1);
    });

    // ===== Tab aktif mengikuti scroll =====
    var links = Array.from(document.querySelectorAll('#lsTabs a.t'));
    var peta = {};
    links.forEach(function (a) { peta[a.getAttribute('href').slice(1)] = a; });
    function aktifkan(id) {
        links.forEach(function (a) { a.classList.toggle('active', a === peta[id]); });
        if (peta[id]) peta[id].scrollIntoView({ block: 'nearest', inline: 'center' });
    }
    if ('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) { if (en.isIntersecting) aktifkan(en.target.id); });
        }, { rootMargin: '-25% 0px -65% 0px' });
        Object.keys(peta).forEach(function (id) {
            var el = document.getElementById(id); if (el) obs.observe(el);
        });
    }
    var kurangGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    links.forEach(function (a) {
        a.addEventListener('click', function (e) {
            var el = document.getElementById(a.getAttribute('href').slice(1));
            if (!el) return;
            e.preventDefault();
            el.scrollIntoView({ behavior: kurangGerak ? 'auto' : 'smooth', block: 'start' });
            aktifkan(el.id);
        });
    });

    // ===== Favorit (disimpan di browser; tombol galeri & tab saling sinkron) =====
    var favBtns = document.querySelectorAll('[data-fav]');
    var favKey = 'ls-fav-{{ $listing->slug }}';
    function setFav(on) {
        favBtns.forEach(function (b) {
            b.classList.toggle('on', on);
            b.setAttribute('aria-pressed', on ? 'true' : 'false');
            b.querySelector('i').className = 'ti ' + (on ? 'ti-heart-filled' : 'ti-heart');
        });
    }
    try { setFav(localStorage.getItem(favKey) === '1'); } catch (e) {}
    favBtns.forEach(function (b) {
        b.addEventListener('click', function () {
            var on = !b.classList.contains('on');
            setFav(on);
            try { on ? localStorage.setItem(favKey, '1') : localStorage.removeItem(favKey); } catch (e) {}
        });
    });

    // ===== Bagikan =====
    var shareTxt = document.getElementById('lsShareTxt');
    document.querySelectorAll('[data-share]').forEach(function (b) {
        b.addEventListener('click', function () {
            var data = { title: document.title, url: location.href };
            if (navigator.share) { navigator.share(data).catch(function () {}); return; }
            if (navigator.clipboard) {
                navigator.clipboard.writeText(location.href).then(function () {
                    shareTxt.textContent = 'Tautan disalin';
                    setTimeout(function () { shareTxt.textContent = 'Bagikan'; }, 1800);
                });
            }
        });
    });

    // ===== Deskripsi: potong + "Baca selengkapnya" =====
    var dWrap = document.getElementById('lsDescWrap');
    var dBtn = document.getElementById('lsDescBtn');
    if (dWrap && dBtn) {
        var dTxt = dBtn.querySelector('span');
        // Tampilkan tombol hanya kalau teks memang lebih panjang dari batas potong
        dWrap.classList.add('is-clamp');
        if (dWrap.scrollHeight - dWrap.clientHeight > 24) {
            dBtn.hidden = false;
        } else {
            dWrap.classList.remove('is-clamp');
        }
        dBtn.addEventListener('click', function () {
            var buka = !dWrap.classList.contains('open');
            dWrap.classList.toggle('open', buka);
            dBtn.setAttribute('aria-expanded', buka ? 'true' : 'false');
            dTxt.textContent = buka ? 'Tampilkan lebih sedikit' : 'Baca selengkapnya';
            if (!buka) document.getElementById('deskripsi').scrollIntoView({ behavior: kurangGerak ? 'auto' : 'smooth', block: 'start' });
        });
    }

    // ===== Jadwal survei: tanggal & waktu lain =====
    var other = document.getElementById('lsOther');
    var custom = document.getElementById('lsCustom');
    other.addEventListener('click', function () {
        custom.hidden = !custom.hidden;
        other.setAttribute('aria-expanded', custom.hidden ? 'false' : 'true');
        if (!custom.hidden) document.getElementById('lsTgl').focus();
    });
    document.getElementById('lsKirim').addEventListener('click', function () {
        var tgl = document.getElementById('lsTgl');
        var jam = document.getElementById('lsJam').value;
        if (!tgl.value) { tgl.focus(); return; }
        var t = new Date(tgl.value + 'T00:00:00').toLocaleDateString('id-ID',
            { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        var pesan = 'Halo, saya ingin menjadwalkan survei properti "' + @json($listing->judul) +
                    '" pada ' + t + (jam ? ' pukul ' + jam : '') + '.';
        window.open('https://wa.me/{{ $wa }}?text=' + encodeURIComponent(pesan), '_blank', 'noopener');
    });

})();
</script>
@endsection