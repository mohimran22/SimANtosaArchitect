@extends('layouts.website')

@section('content')
<div class="container py-5">
    <h1 class="mb-1">Artikel</h1>
    <p class="text-muted mb-4">Wawasan seputar arsitektur, desain, dan pembangunan rumah.</p>

    @if($articles->isEmpty())
        <p>Belum ada artikel.</p>
    @else
        <div class="row g-4">
            @foreach($articles as $a)
                <div class="col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <a href="{{ route('articles.show', $a) }}" class="text-decoration-none text-reset">
                            @if($a->featured_image)
                                <img src="{{ asset('storage/'.$a->featured_image) }}" alt="{{ $a->title }}"
                                     loading="lazy" class="card-img-top"
                                     style="aspect-ratio:16/10;object-fit:cover">
                            @endif
                            <div class="card-body">
                                @if($a->categories->isNotEmpty())
                                    <div class="small text-uppercase text-muted">
                                        {{ $a->categories->pluck('name')->implode(' · ') }}
                                    </div>
                                @endif
                                <h2 class="h5 card-title mt-1">{{ $a->title }}</h2>
                                <p class="card-text text-muted small">
                                    {{ $a->excerpt ?: \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags(str_replace('<', ' <', $a->content)))), 140) }}
                                </p>
                                <time class="small text-muted" datetime="{{ $a->published_at->toDateString() }}">
                                    {{ $a->published_at->translatedFormat('d F Y') }}
                                </time>
                            </div>
                        </a>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $articles->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection