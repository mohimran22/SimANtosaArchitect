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
.pf-agent img{ width:36px; height:36px; border-radius:50%; object-fit:cover; background:#eee; }
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
.lh{ position:relative; background:radial-gradient(ellipse at 70% 30%,#2a2a2a 0%,#0b0b0b 65%); color:#fff; font-family:'Poppins',sans-serif; overflow:hidden; }
.lh::before, .lh::after{ /* siluet rumah dekoratif */
    content:""; position:absolute; bottom:0; width:230px; height:330px; background:rgba(255,176,0,.09);
    clip-path:polygon(0 38%,50% 0,100% 38%,100% 100%,0 100%);
}
.lh::before{ right:-40px; }
.lh::after{ right:200px; height:230px; width:170px; opacity:.7; }
.lh-inner{
    position:relative; z-index:1; width:min(100% - 40px,1140px); margin:0 auto;
    display:grid; grid-template-columns:1fr 1fr; align-items:end; min-height:480px;
}
.lh-img{ align-self:end; display:flex; justify-content:flex-start; }
.lh-img img{ display:block; width:100%; max-width:470px; height:auto; max-height:480px; object-fit:contain; object-position:bottom left; }
.lh-text{ padding:70px 0 90px; align-self:center; }
.lh.no-img .lh-inner{ grid-template-columns:1fr; }
.lh.no-img .lh-text{ max-width:720px; }
.lh h1{ font-family:'Poppins',sans-serif; font-weight:700; font-size:clamp(30px,4vw,52px); line-height:1.2; margin:0 0 12px; }
.lh-tag{ font-size:clamp(18px,2.2vw,29px); font-weight:400; margin:0 0 34px; }
.lh-tag b{ font-weight:400; color:var(--accent); }
.lh-toggle{ display:inline-flex; background:#fff; padding:5px; border-radius:999px; box-shadow:0 6px 20px rgba(0,0,0,.25); }
.lh-toggle label{ cursor:pointer; margin:0; }
.lh-toggle input{ position:absolute; opacity:0; pointer-events:none; }
.lh-toggle span{ display:block; padding:9px 26px; border-radius:999px; font-weight:600; font-size:15px; color:#444; transition:background .2s,color .2s; }
.lh-toggle input:checked + span{ background:var(--accent); color:#000; }
.lh-toggle input:focus-visible + span{ outline:2px solid #000; outline-offset:2px; }

.land{ background:#fff; padding:0 0 90px; font-family:'Poppins',sans-serif; color:#222; }
.land-wrap{ width:min(100% - 40px,1140px); margin:0 auto; }
.ls-bar{
    display:flex; align-items:center; gap:0; background:#fff; border-radius:999px; padding:8px 8px 8px 20px;
    width:min(100% - 40px,768px); margin:-34px auto 0; position:relative; z-index:3;
    box-shadow:0 12px 36px rgba(0,0,0,.18);
}
.ls-cat{ position:relative; flex:0 0 auto; padding-right:16px; border-right:1px solid #e6e6e6; display:flex; align-items:center; gap:8px; color:#555; }
.ls-cat select{ appearance:none; border:0; background:transparent; font:inherit; font-size:15px; font-weight:500; color:#222; padding:10px 22px 10px 0; cursor:pointer; max-width:130px; }
.ls-cat::after{ content:""; position:absolute; right:22px; top:50%; width:6px; height:6px; margin-top:-5px; border-right:1.5px solid #888; border-bottom:1.5px solid #888; transform:rotate(45deg); pointer-events:none; }
.ls-q{ flex:1; display:flex; align-items:center; gap:10px; padding:0 14px; min-width:0; color:#888; }
.ls-q input{ flex:1; border:0; outline:0; background:transparent; font:inherit; font-size:15px; color:#222; min-width:0; }
.ls-btn{
    flex:0 0 auto; display:inline-flex; align-items:center; gap:8px; height:48px; padding:0 26px; border:0; border-radius:999px;
    background:#000; color:#fff; font:inherit; font-size:15px; font-weight:600; cursor:pointer; transition:background .2s,color .2s;
}
.ls-btn:hover{ background:var(--accent); color:#000; }
.ls-pop{ display:flex; flex-wrap:wrap; justify-content:center; align-items:center; gap:10px; margin:26px 0 38px; font-size:13px; color:#888; }
.ls-pop a{
    display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border:1px solid #ececec; border-radius:999px; background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,.05); color:#222; font-size:13px; text-decoration:none; transition:border-color .2s,background .2s;
}
.ls-pop a:hover, .ls-pop a.on{ border-color:var(--accent); background:#fff8e6; }
.ls-pop i{ color:#e0a000; }
.land-meta{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin:0 0 20px; }
.land-meta select{ height:38px; border:1px solid #e3e3e3; padding:0 10px; font:inherit; font-size:14px; background:#fff; }
.land-count{ font-size:14px; color:#777; margin:0 0 20px; }
.land-empty{ padding:60px 0; text-align:center; color:#777; }
.land-pager{ margin-top:50px; padding-left:0; }
.pg{ display:flex; flex-wrap:wrap; gap:4px; }
.pg-i{ display:inline-flex; align-items:center; justify-content:center; min-width:29px; height:35px; padding:0 10px; background:#ebebeb; color:#777; font-size:16px; text-decoration:none; transition:background .2s,color .2s; }
a.pg-i:hover{ background:#dcdcdc; color:#000; }
.pg-on{ background:#e2e2e2; color:#111; }
.pg-dots{ background:transparent; }
@media (max-width:900px){
    .lh-inner{ grid-template-columns:1fr; min-height:0; text-align:center; }
    .lh-img{ order:2; justify-content:center; }
    .lh-img img{ max-width:300px; max-height:300px; }
    .lh-text{ padding:56px 0 20px; }
    .lh.no-img .lh-text{ padding-bottom:70px; margin:0 auto; }
    .lh::before, .lh::after{ opacity:.5; }
}
@media (max-width:640px){
    .ls-bar{ flex-direction:column; align-items:stretch; border-radius:22px; padding:12px; gap:8px; margin-top:-26px; }
    .ls-cat{ border-right:0; border-bottom:1px solid #eee; padding:0 0 8px; }
    .ls-cat select{ max-width:none; width:100%; }
    .ls-cat::after{ right:6px; margin-top:-8px; }
    .ls-q{ padding:6px 4px; }
    .ls-btn{ justify-content:center; width:100%; }
    .lh-toggle span{ padding:8px 22px; }
    .ls-pop{ margin:20px 0 28px; }
}
</style>
@endpush

@section('content')
@php
    $lsImg = fn ($path) => ! $path ? asset('images/antosa.png') : (str_starts_with($path, 'http') ? $path : (str_starts_with($path, 'images/') ? asset($path) : asset('storage/' . $path)));
    $lsM2  = fn ($v) => is_numeric($v) ? $v . 'm²' : $v;
    $lsRp  = fn ($v) => is_numeric($v) ? 'Rp ' . number_format($v, 0, ',', '.') : $v;
    $lsWa  = function ($no) { $n = preg_replace('/\D/', '', (string) $no); return str_starts_with($n, '0') ? '62' . substr($n, 1) : $n; };
    $waMarketing = $lsWa(config('antosa.wa_marketing'));
    $f = request();
@endphp

@php
    $heroImg  = 'images/land-hero.png';              // foto di sisi kiri hero (opsional)
    $hasHero  = file_exists(public_path($heroImg));
@endphp
<form method="GET" action="{{ route('land.index') }}" id="landForm">
<section class="lh {{ $hasHero ? '' : 'no-img' }}">
    <div class="lh-inner">
        @if ($hasHero)
            <div class="lh-img"><img src="{{ asset($heroImg) }}" alt="Cari properti di Antosa Land"></div>
        @endif
        <div class="lh-text">
            <h1>Antosa Land</h1>
            <p class="lh-tag"><b>#TemukanHunianImpian</b> di Antosa Land</p>
            <div class="lh-toggle" role="radiogroup" aria-label="Jenis penawaran">
                <label><input type="radio" name="status" value="dijual" @checked($status === 'dijual') onchange="this.form.submit()"><span>Dijual</span></label>
                <label><input type="radio" name="status" value="disewa" @checked($status === 'disewa') onchange="this.form.submit()"><span>Disewa</span></label>
            </div>
        </div>
    </div>
</section>

<div class="ls-bar">
    <label class="ls-cat">
        <i class="ti ti-layout-grid"></i>
        <select name="tipe" aria-label="Kategori properti">
            <option value="">Kategori</option>
            @foreach ($tipeList as $t)
                <option value="{{ $t }}" @selected($f->query('tipe') === $t)>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
    </label>
    <label class="ls-q">
        <i class="ti ti-search"></i>
        <input type="search" name="q" value="{{ $f->query('q') }}" placeholder="Cari lokasi, area atau nama properti...">
    </label>
    @if ($f->query('kota')) <input type="hidden" name="kota" value="{{ $f->query('kota') }}"> @endif
    @if ($f->query('urut')) <input type="hidden" name="urut" value="{{ $f->query('urut') }}"> @endif
    <button type="submit" class="ls-btn"><i class="ti ti-search"></i> Cari Properti</button>
</div>
</form>

<section class="land">
    <div class="land-wrap">
        @if ($kotaPopuler->isNotEmpty())
            <div class="ls-pop">
                <span>Lokasi populer:</span>
                @foreach ($kotaPopuler as $k)
                    <a href="{{ route('land.index', ['kota' => $k, 'status' => $status]) }}" class="{{ $f->query('kota') === $k ? 'on' : '' }}"><i class="ti ti-map-pin"></i> {{ $k }}</a>
                @endforeach
                @if ($f->query('kota'))
                    <a href="{{ route('land.index', ['status' => $status]) }}">Semua lokasi &times;</a>
                @endif
            </div>
        @endif

        <div class="land-meta">
            <p class="land-count" style="margin:0">{{ $listings->total() }} properti ditemukan</p>
            <form method="GET" action="{{ route('land.index') }}">
                @foreach (['q','tipe','kota','status'] as $keep)
                    @if ($f->query($keep)) <input type="hidden" name="{{ $keep }}" value="{{ $f->query($keep) }}"> @endif
                @endforeach
                <select name="urut" aria-label="Urutkan" onchange="this.form.submit()">
                    <option value="">Terbaru</option>
                    <option value="termurah" @selected($f->query('urut') === 'termurah')>Harga Termurah</option>
                    <option value="termahal" @selected($f->query('urut') === 'termahal')>Harga Termahal</option>
                </select>
            </form>
        </div>

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
                                    <img src="{{ $lsImg($l->agen_foto) }}" alt="{{ $l->agen_nama }}">
                                    <div><strong>{{ $l->agen_nama }}</strong></div>
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