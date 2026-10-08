@extends('layouts.public')

@section('content')
<h1>Artikel</h1>
@foreach($articles as $a)
    <article>
        <h2><a href="{{ route('articles.show', $a) }}">{{ $a->title }}</a></h2>
        <p>{{ $a->excerpt }}</p>
    </article>
@endforeach
{{ $articles->links() }}
@endsection