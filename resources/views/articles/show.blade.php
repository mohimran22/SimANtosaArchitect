@extends('layouts.public')

@section('content')
<article>
    <h1>{{ $article->title }}</h1>
    <p><small>{{ $article->author->name }} · {{ $article->published_at->translatedFormat('d F Y') }}</small></p>

    @if($article->featured_image)
        <img src="{{ asset('storage/'.$article->featured_image) }}" alt="{{ $article->title }}" style="max-width:100%">
    @endif

    <div class="article-content">{!! $article->content !!}</div>
</article>
@endsection