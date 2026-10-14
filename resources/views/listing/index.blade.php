@extends('layouts.website')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Saira:wght@600;700&display=swap" rel="stylesheet">
<style>
:root{ --accent:#ffb000; }
.pf{ background:#fff; padding:80px 0 90px; font-family:'Poppins',sans-serif; color:#222; }
.pf-wrap{ width:min(100% - 40px,1140px); margin:0 auto; }
.pf h2{ font-size:24px; font-weight:700; margin:0 0 6px; }
.pf-sub{ font-size:15px; color:#8a8a8a; margin:0 0 28px; }
.pf-tabs{ display:flex; gap:6px; border-bottom:1px solid #eee; margin-bottom:26px; overflow-x:auto; }
.pf-tab{
    background:none; border:none; border-bottom:2px solid transparent;
    padding:14px 16px; font-family:inherit; font-size:15px; color:#555;
    cursor:pointer; white-space:nowrap; margin-bottom:-1px; transition:.2s;
}
.pf-tab:hover{ color:#000; }
.pf-tab.active{ color:#000; border-bottom-color:var(--accent); font-weight:500; }

.pf-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; align-items:start; }
.pf-card{
    background:#fff; border:1px solid #ececec; border-radius:14px; overflow:hidden;
    box-shadow:0 2px 10px rgba(0,0,0,.04); transition:box-shadow .25s, transform .25s;
}
.pf-card:hover{ box-shadow:0 12px 30px rgba(0,0,0,.1); transform:translateY(-3px); }
.pf-card[hidden]{ display:none; }
.pf-img{ position:relative; display:block; height:224px; }
.pf-img img{ width:100%; height:100%; object-fit:cover; display:block; }
.pf-photos{
    position:absolute; right:12px; bottom:12px;
    background:rgba(0,0,0,.65); color:#fff; font-size:12px; font-weight:600;
    padding:5px 9px; border-radius:8px; display:flex; align-items:center; gap:5px;
}
.pf-body{ padding:16px; }
.pf-badges{ display:flex; gap:8px; margin-bottom:14px; }
.pf-badge{
    height:26px; padding:0 10px; display:inline-flex; align-items:center; gap:5px;
    font-size:12px; font-weight:700; text-transform:uppercase; border-radius:4px;
}
.pf-badge.main{ background:#000; color:#fff; }
.pf-badge.type{ border:1px solid #e3e3e3; color:#444; font-weight:500; }
.pf-label{ font-size:12px; color:#999; text-transform:uppercase; margin:0; }
.pf-price{ font-size:22px; font-weight:700; margin:2px 0 8px; }
.pf-cicilan{
    display:inline-flex; align-items:center; gap:6px; background:#fff6e0; color:#555;
    font-size:13px; padding:5px 10px; border-radius:6px; margin:0 0 8px;
}
.pf-cicilan b{ color:#c77700; font-weight:600; }
.pf-loc{ font-size:13px; color:#666; display:flex; align-items:center; gap:5px; margin:0 0 6px; }
.pf-loc i{ color:#e0a000; }
.pf-title{
    font-size:16px; font-weight:600; line-height:1.4; margin:0 0 12px; padding-left:10px;
    border-left:3px solid var(--accent);
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.pf-title a{ color:inherit; text-decoration:none; }
.pf-specs{ display:flex; background:#f6f6f6; border-radius:10px; padding:8px 0; }
.pf-spec{ flex:1; text-align:center; border-left:1px solid #e8e8e8; }
.pf-spec:first-child{ border-left:none; }
.pf-spec b{ display:block; font-size:16px; font-weight:700; line-height:1.4; }
.pf-spec span{ font-size:11px; color:#8a8a8a; text-transform:uppercase; display:inline-flex; align-items:center; gap:4px; }
.pf-foot{
    display:flex; align-items:center; gap:10px;
    border-top:1px solid #f0f0f0; margin-top:18px; padding-top:16px;
}
.pf-agent{ display:flex; align-items:center; gap:10px; flex:1; min-width:0; }
.pf-agent img{ width:36px; height:36px; border-radius:50%; object-fit:contain; background:#fff;
               border:1px solid #ececf0; padding:6px; flex:none; }
.pf-agent strong{ display:block; font-size:13px; font-weight:600; line-height:1.3; }
.pf-agent small{ font-size:11px; color:#777; display:flex; align-items:center; gap:5px; }
.pf-agent small::before{ content:""; width:6px; height:6px; border-radius:50%; background:#f5a400; }
.pf-call{
    width:40px; height:36px; border:1px solid #e3e3e3; border-radius:8px; background:#fff;
    display:flex; align-items:center; justify-content:center; color:#333; font-size:18px; text-decoration:none;
}
.pf-wa{
    height:36px; padding:0 14px; border-radius:8px; background:#22c55e; color:#fff;
    display:inline-flex; align-items:center; gap:6px; font-size:14px; font-weight:600; text-decoration:none;
}
.pf-wa:hover{ background:#16a34a; color:#fff; }

@media (max-width:1000px){ .pf-grid{ grid-template-columns:repeat(2,1fr); } }
@media (max-width:640px){
    .pf{ padding:60px 0; }
    .pf-grid{ grid-template-columns:1fr; }
}

/* ===== Halaman Antosa Land ===== */
.land-hero{ background:linear-gradient(135deg,#000 0%,#1c1c1c 100%); color:#fff; padding:90px 0 70px; font-family:'Poppins',sans-serif; text-align:center; }
.land-hero h1{ font-family:'Saira',sans-serif; font-weight:700; font-size:clamp(34px,4vw,56px); margin:0 0 14px; }
.land-hero p{ font-size:clamp(15px,1.3vw,18px); color:#d8d8d8; margin:0 auto; max-width:640px; }
.land-hero h1 span{ color:var(--accent); }
.land{ background:#fff; padding:0 0 90px; font-family:'Poppins',sans-serif; color:#222; }
.land-wrap{ width:min(100% - 40px,1140px); margin:0 auto; }
.land-filter{
    display:grid; grid-template-columns:2fr 1fr 1fr 1fr auto; gap:10px;
    background:#fff; border:1px solid #ececec; box-shadow:0 8px 28px rgba(0,0,0,.08);
    padding:16px; margin:-34px 0 34px; position:relative; z-index:2;
}
.land-filter input, .land-filter select{
    height:44px; border:1px solid #e3e3e3; padding:0 14px; font:inherit; font-size:14px; background:#fff; width:100%; min-width:0;
}
.land-filter input:focus, .land-filter select:focus{ outline:2px solid var(--accent); outline-offset:-1px; }
.land-filter button{
    height:44px; padding:0 26px; border:0; background:#000; color:#fff; font:inherit; font-size:14px; font-weight:600; cursor:pointer; transition:background .2s;
}
.land-filter button:hover{ background:var(--accent); color:#000; }
.land-count{ font-size:14px; color:#777; margin:0 0 20px; }
.land-empty{ padding:60px 0; text-align:center; color:#777; }
.land-pager{ margin-top:50px; padding-left:0; }
.pg{ display:flex; flex-wrap:wrap; gap:4px; }
.pg-i{ display:inline-flex; align-items:center; justify-content:center; min-width:29px; height:35px; padding:0 10px; background:#ebebeb; color:#777; font-size:16px; text-decoration:none; transition:background .2s,color .2s; }
a.pg-i:hover{ background:#dcdcdc; color:#000; }
.pg-on{ background:#e2e2e2; color:#111; }
.pg-dots{ background:transparent; }
@media (max-width:900px){ .land-filter{ grid-template-columns:1fr 1fr; } .land-filter input{ grid-column:1 / -1; } .land-filter button{ grid-column:1 / -1; } }
@media (max-width:560px){ .land-hero{ padding:60px 0 56px; } .land-filter{ grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
@php
    $lsImg = fn ($path) => ! $path ? asset('images/antosa.png') : (str_starts_with($path, 'http') ? $path : (str_starts_with($path, 'images/') ? asset($path) : asset('storage/' . $path)));
    $agenFoto = asset('images/logo-icon.png');
    $agenNama = 'Antosa Land';
    $lsM2  = fn ($v) => is_numeric($v) ? $v . 'm²' : $v;
    $lsRp  = fn ($v) => is_numeric($v) ? 'Rp ' . number_format($v, 0, ',', '.') : $v;
    $lsWa  = function ($no) { $n = preg_replace('/\D/', '', (string) $no); return str_starts_with($n, '0') ? '62' . substr($n, 1) : $n; };
    $waMarketing = $lsWa(config('antosa.wa_marketing'));
    $f = request();
@endphp

<section class="land-hero">
    <div class="land-wrap">
        <h1>Antosa <span>Land</span></h1>
        <p>Temukan rumah dan properti pilihan langsung dari pemilik &amp; agen terverifikasi.</p>
    </div>
</section>

<section class="land">
    <div class="land-wrap">
        <form method="GET" action="{{ route('land.index') }}" class="land-filter">
            <input type="search" name="q" value="{{ $f->query('q') }}" placeholder="Cari judul atau lokasi properti...">
            <select name="tipe" aria-label="Tipe properti">
                <option value="">Semua Tipe</option>
                @foreach ($tipeList as $t)
                    <option value="{{ $t }}" @selected($f->query('tipe') === $t)>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
            <select name="kota" aria-label="Kota">
                <option value="">Semua Lokasi</option>
                @foreach ($kotaList as $k)
                    <option value="{{ $k }}" @selected($f->query('kota') === $k)>{{ $k }}</option>
                @endforeach
            </select>
            <select name="urut" aria-label="Urutkan">
                <option value="">Terbaru</option>
                <option value="termurah" @selected($f->query('urut') === 'termurah')>Harga Termurah</option>
                <option value="termahal" @selected($f->query('urut') === 'termahal')>Harga Termahal</option>
            </select>
            <button type="submit">Cari</button>
        </form>

        <p class="land-count">{{ $listings->total() }} properti ditemukan</p>

        @if ($listings->isEmpty())
            <div class="land-empty">
                Belum ada properti yang cocok.
                @if ($f->query('q') || $f->query('tipe') || $f->query('kota'))
                    <br><a href="{{ route('land.index') }}">Reset pencarian</a>
                @endif
            </div>
        @else
            <div class="pf-grid">
                @foreach ($listings as $l)
                    @php $status = ucfirst($l->status ?: 'dijual'); $url = $l->url; @endphp
                    <article class="pf-card">
                        <a href="{{ $url }}" class="pf-img">
                            <img src="{{ $lsImg($l->foto) }}" alt="{{ $l->judul }}" loading="lazy">
                            @if ($l->jumlah_foto)
                                <span class="pf-photos"><i class="ti ti-camera"></i> {{ $l->jumlah_foto }}</span>
                            @endif
                        </a>
                        <div class="pf-body">
                            <div class="pf-badges">
                                <span class="pf-badge main">{{ $status }}</span>
                                <span class="pf-badge type"><i class="ti ti-home"></i> {{ ucfirst($l->tipe) }}</span>
                            </div>
                            <p class="pf-label">{{ strtolower($status) === 'disewa' ? 'Harga Sewa' : 'Harga Jual' }}</p>
                            <div class="pf-price">{{ $lsRp($l->harga) }}</div>
                            @if (is_numeric($l->cicilan) && $l->cicilan > 0)
                                <div class="pf-cicilan"><i class="ti ti-credit-card"></i> Cicilan mulai <b>Rp {{ number_format((float) $l->cicilan, 0, ',', '.') }}/bln</b></div>
                            @endif
                            <p class="pf-loc"><i class="ti ti-map-pin-filled"></i> {{ $l->lokasi_lengkap }}</p>
                            <h3 class="pf-title"><a href="{{ $url }}">{{ $l->judul }}</a></h3>
                            <div class="pf-specs">
                                @if ($l->kt)<div class="pf-spec"><b>{{ $l->kt }}</b><span><i class="ti ti-bed"></i> KT</span></div>@endif
                                @if ($l->km)<div class="pf-spec"><b>{{ $l->km }}</b><span><i class="ti ti-bath"></i> KM</span></div>@endif
                                @if ($l->lt)<div class="pf-spec"><b>{{ $lsM2($l->lt) }}</b><span><i class="ti ti-arrows-maximize"></i> LT</span></div>@endif
                                @if ($l->lb)<div class="pf-spec"><b>{{ $lsM2($l->lb) }}</b><span><i class="ti ti-building"></i> LB</span></div>@endif
                            </div>
                            <div class="pf-foot">
                                <div class="pf-agent">
                                    <img src="{{ $agenFoto }}" alt="{{ $agenNama }}">
                                    <div><strong>{{ $agenNama }}</strong></div>
                                </div>
                                <a href="tel:+{{ $waMarketing }}" class="pf-call" aria-label="Telepon"><i class="ti ti-phone"></i></a>
                                <a href="https://wa.me/{{ $waMarketing }}?text={{ urlencode('Halo, saya tertarik dengan listing: ' . $l->judul) }}" target="_blank" rel="noopener" class="pf-wa"><i class="ti ti-brand-whatsapp"></i> WhatsApp</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="land-pager">
                {{ $listings->links('pagination.artikel') }}
            </div>
        @endif
    </div>
</section>
@endsection