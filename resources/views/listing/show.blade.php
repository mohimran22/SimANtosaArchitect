@extends('layouts.website')
@section('content')
@php
    $wa = preg_replace('/\D/', '', (string) ($listing->agen_telepon ?: '6285189523863'));
    $wa = str_starts_with($wa, '0') ? '62' . substr($wa, 1) : $wa;
@endphp
<section style="padding:60px 0;font-family:'Poppins',sans-serif">
    <div style="width:min(100% - 40px,1000px);margin:0 auto">
        <a href="{{ url('/') }}#listing" style="color:#555;text-decoration:none">← Kembali</a>
        <h1 style="font-size:30px;margin:14px 0 6px">{{ $listing->judul }}</h1>
        <p style="color:#777"><i class="ti ti-map-pin-filled"></i> {{ $listing->alamat_lengkap }}</p>

        @if ($listing->foto)
            <img src="{{ asset('storage/'.$listing->foto) }}" alt="{{ $listing->judul }}" style="width:100%;max-height:520px;object-fit:cover;border-radius:12px">
        @endif
        @if ($listing->fotos->isNotEmpty())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;margin-top:10px">
                @foreach ($listing->fotos as $f)
                    <img src="{{ asset('storage/'.$f->path) }}" alt="" style="width:100%;height:130px;object-fit:cover;border-radius:8px">
                @endforeach
            </div>
        @endif

        <div style="margin:24px 0;font-size:28px;font-weight:700">Rp {{ number_format($listing->harga, 0, ',', '.') }}</div>
        <p>
            @if($listing->kt) {{ $listing->kt }} KT · @endif
            @if($listing->km) {{ $listing->km }} KM · @endif
            @if($listing->lt) LT {{ $listing->lt }}m² · @endif
            @if($listing->lb) LB {{ $listing->lb }}m² @endif
        </p>
        <div style="white-space:pre-line;line-height:1.7;margin:20px 0">{{ $listing->deskripsi }}</div>

        <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo, saya tertarik dengan listing: '.$listing->judul) }}" target="_blank" rel="noopener"
           style="display:inline-flex;gap:8px;align-items:center;background:#22c55e;color:#fff;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:600">
            <i class="ti ti-brand-whatsapp"></i> Hubungi via WhatsApp
        </a>
    </div>
</section>
@endsection