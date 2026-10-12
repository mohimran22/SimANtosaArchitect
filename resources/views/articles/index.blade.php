@extends('layouts.website')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Poppins:wght@400;500;600&family=Saira:wght@500;600;700&display=swap" rel="stylesheet">
<style>
.art{ font-family:'Poppins',sans-serif; color:#222; background:#fff; padding:0 0 90px; }
.art-wrap{ width:min(100% - 40px,1200px); margin:0 auto; }

/* banner */
.art-banner{ width:100%; aspect-ratio:16/9; background:#1b1b1b center/cover no-repeat; }
.art-banner img{ width:100%; height:100%; object-fit:cover; display:block; }

/* judul halaman */
.art-title{
    font-family:'Montserrat',sans-serif; font-weight:700; color:#16213e;
    font-size:clamp(26px,2.4vw,38px); line-height:1.25; margin:18px 0 8px 0;
}
.art-sub{ margin:0 0 26px 0; font-size:15px; color:#666; }

/* daftar artikel */
.art-list{ display:flex; flex-direction:column; gap:20px; }
.art-row{
    display:grid; grid-template-columns:378px 1fr; column-gap:22px; align-items:center;
    background:#f6f6f6; padding:22px 50px; transition:box-shadow .25s, transform .25s;
}
.art-row:hover{ box-shadow:0 10px 28px rgba(0,0,0,.08); }
.art-thumb{ display:block; aspect-ratio:378/264; overflow:hidden; background:#e4e4e4; }
.art-thumb img{ width:100%; height:100%; object-fit:cover; display:block; transition:transform .5s; }
.art-row:hover .art-thumb img{ transform:scale(1.04); }
.art-cat{ font-size:12px; font-weight:600; letter-spacing:1.5px; text-transform:uppercase; color:#8a8a8a; margin:0 0 8px; }
.art-h{ font-family:'Saira',sans-serif; font-weight:600; font-size:clamp(20px,1.7vw,26px); line-height:1.3; margin:0 0 16px; }
.art-h a{ color:#000; text-decoration:none; }
.art-h a:hover{ color:#1e5aa8; }
.art-ex{ font-size:15px; line-height:1.7; color:#555; margin:0 0 16px; max-width:640px; }
.art-date{ display:block; font-size:13px; color:#888; margin:0 0 18px; }
.art-btn{
    display:inline-flex; align-items:center; justify-content:center; min-width:207px; height:37px; padding:0 20px;
    background:#1e5aa8; color:#fff; font-family:'Saira',sans-serif; font-weight:500; font-size:15px;
    text-decoration:none; transition:background .2s;
}
.art-btn:hover{ background:#164682; color:#fff; }
.art-empty{ padding:60px 12px; color:#666; }

/* paginasi (kotak abu-abu datar, rata kiri) */
.art-pager{ margin-top:44px; padding-left:20px; }
.pg{ display:flex; flex-wrap:wrap; gap:4px; font-family:'Poppins',sans-serif; }
.pg-i{
    display:inline-flex; align-items:center; justify-content:center; min-width:29px; height:35px; padding:0 10px;
    background:#ebebeb; color:#777; font-size:16px; text-decoration:none; transition:background .2s, color .2s;
}
a.pg-i:hover{ background:#dcdcdc; color:#000; }
.pg-on{ background:#e2e2e2; color:#111; }
.pg-dots{ background:transparent; }

@media (max-width:1000px){
    .art-row{ grid-template-columns:280px 1fr; padding:20px 24px; }
}
@media (max-width:760px){
    .art-row{ grid-template-columns:1fr; row-gap:18px; padding:16px; }
    .art-btn{ min-width:0; width:100%; }
}
</style>
@endpush

@section('content')
@php
    $bannerFile = 'images/banner-artikel.webp';
    $hasBanner  = file_exists(public_path($bannerFile));
@endphp
<section class="art">
    <div class="art-wrap">
        @if ($hasBanner)
            <div class="art-banner"><img src="{{ asset($bannerFile) }}" alt="Artikel Antosa Architect"></div>
        @endif

        <h1 class="art-title">Tips Seputar Desain Dan Konstruksi Rumah</h1>

        @if ($articles->isEmpty())
            <p class="art-empty">Belum ada artikel.</p>
        @else
            <div class="art-list">
                @foreach ($articles as $a)
                    @php
                        $excerpt = $a->excerpt ?: \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags(str_replace('<', ' <', $a->content)))), 150);
                    @endphp
                    <article class="art-row">
                        <a href="{{ route('articles.show', $a) }}" class="art-thumb" tabindex="-1" aria-hidden="true">
                            @if ($a->featured_image)
                                <img src="{{ asset('storage/'.$a->featured_image) }}" alt="{{ $a->title }}" loading="lazy">
                            @endif
                        </a>
                        <div>
                            @if ($a->categories->isNotEmpty())
                                <p class="art-cat">{{ $a->categories->pluck('name')->implode(' · ') }}</p>
                            @endif
                            <h2 class="art-h"><a href="{{ route('articles.show', $a) }}">{{ $a->title }}</a></h2>
                            <p class="art-ex">{{ $excerpt }}</p>
                            @if ($a->published_at)
                                <time class="art-date" datetime="{{ $a->published_at->toDateString() }}">{{ $a->published_at->translatedFormat('d F Y') }}</time>
                            @endif
                            <a href="{{ route('articles.show', $a) }}" class="art-btn">Baca Selengkapnya</a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="art-pager">
                {{ $articles->links('pagination.artikel') }}
            </div>
        @endif
    </div>
</section>
@endsection