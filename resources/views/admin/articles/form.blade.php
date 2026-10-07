@extends('tablar::page')

@section('content')
@php $isEdit = $article->exists; @endphp

<form method="POST" enctype="multipart/form-data"
      action="{{ $isEdit ? route('articles.update', $article) : route('articles.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <h2>{{ $isEdit ? 'Edit' : 'Tulis' }} Artikel</h2>

    @if($errors->any())
        <ul style="color:#b00">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    @endif

    <label>Judul</label>
    <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required style="width:100%">

    <label>Slug (kosongkan untuk otomatis)</label>
    <input type="text" id="slug" name="slug" value="{{ old('slug', $article->slug) }}" style="width:100%">

    <label>Konten</label>
    <textarea id="content" name="content">{{ old('content', $article->content) }}</textarea>

    <label>Ringkasan (excerpt)</label>
    <textarea id="excerpt" name="excerpt" maxlength="300" rows="3" style="width:100%">{{ old('excerpt', $article->excerpt) }}</textarea>

    <label>Gambar utama</label>
    <input type="file" name="featured_image" accept="image/*">
    @if($article->featured_image)
        <img src="{{ asset('storage/'.$article->featured_image) }}" width="120" alt="">
    @endif

    <label>Status</label>
    <select name="status">
        <option value="draft" @selected(old('status', $article->status) === 'draft')>Draft</option>
        <option value="published" @selected(old('status', $article->status) === 'published')>Terbit</option>
    </select>

    <hr>
    <h3>SEO</h3>

    <label>Focus keyword</label>
    <input type="text" id="focus_keyword" name="focus_keyword"
           value="{{ old('focus_keyword', $article->focus_keyword) }}" style="width:100%">

    <label>Meta title <small id="mt-count"></small></label>
    <input type="text" id="meta_title" name="meta_title" maxlength="70"
           value="{{ old('meta_title', $article->meta_title) }}" style="width:100%">

    <label>Meta description <small id="md-count"></small></label>
    <textarea id="meta_description" name="meta_description" maxlength="170" rows="3" style="width:100%">{{ old('meta_description', $article->meta_description) }}</textarea>

    <label>Canonical URL (opsional)</label>
    <input type="url" name="canonical_url" value="{{ old('canonical_url', $article->canonical_url) }}" style="width:100%">

    <label>Gambar share (OG image, opsional)</label>
    <input type="file" name="og_image" accept="image/*">

    <label><input type="checkbox" name="noindex" value="1" @checked(old('noindex', $article->noindex))> Jangan diindex Google (noindex)</label>

    {{-- Preview Google --}}
    <div style="border:1px solid #ddd;padding:12px;margin:12px 0;font-family:Arial,sans-serif;max-width:600px">
        <div id="pv-url" style="color:#188038;font-size:13px"></div>
        <div id="pv-title" style="color:#1a0dab;font-size:19px"></div>
        <div id="pv-desc" style="color:#4d5156;font-size:13px"></div>
    </div>

    {{-- Hasil analisis --}}
    <ul id="seo-checks" style="list-style:none;padding:0"></ul>

    <button type="submit">Simpan</button>
</form>
@endsection

@push('js')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
const $ = id => document.getElementById(id);
let editor;

ClassicEditor.create($('content'), {
    toolbar: ['heading','|','bold','italic','link','bulletedList','numberedList','blockQuote','insertTable','undo','redo'],
}).then(ed => {
    editor = ed;
    ed.model.document.on('change:data', analyze);
    analyze();
});

['title','slug','focus_keyword','meta_title','meta_description','excerpt'].forEach(id =>
    $(id).addEventListener('input', analyze));

const slugify = s => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'')
    .replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'');

function analyze() {
    const title = $('title').value.trim();
    const slug  = $('slug').value.trim() || slugify(title);
    const kw    = $('focus_keyword').value.trim().toLowerCase();
    const mt    = $('meta_title').value.trim() || title;
    const md    = $('meta_description').value.trim() || $('excerpt').value.trim();
    const html  = editor ? editor.getData() : '';
    const text  = html.replace(/<[^>]+>/g,' ').replace(/\s+/g,' ').trim();
    const words = text ? text.split(' ').length : 0;
    const firstP = (html.match(/<p>(.*?)<\/p>/i) || [,''])[1].replace(/<[^>]+>/g,'').toLowerCase();

    // counter + preview
    $('mt-count').textContent = `(${$('meta_title').value.length}/70)`;
    $('md-count').textContent = `(${$('meta_description').value.length}/170)`;
    $('pv-url').textContent   = `antosaarchitect.com › artikel › ${slug}`;
    $('pv-title').textContent = mt.slice(0, 60) || 'Judul artikel';
    $('pv-desc').textContent  = md.slice(0, 160) || 'Deskripsi artikel akan tampil di sini.';

    const checks = [
        [mt.length >= 30 && mt.length <= 60, 'Panjang meta title ideal 30–60 karakter'],
        [md.length >= 120 && md.length <= 160, 'Panjang meta description ideal 120–160 karakter'],
        [words >= 300, `Jumlah kata minimal 300 (sekarang ${words})`],
        [/<a [^>]*href/i.test(html), 'Ada minimal 1 link di dalam konten'],
        [/<h2/i.test(html), 'Ada subjudul (H2) di dalam konten'],
    ];
    if (kw) {
        checks.push(
            [mt.toLowerCase().includes(kw), 'Focus keyword ada di meta title'],
            [slug.includes(slugify(kw)), 'Focus keyword ada di slug'],
            [md.toLowerCase().includes(kw), 'Focus keyword ada di meta description'],
            [firstP.includes(kw), 'Focus keyword ada di paragraf pertama'],
        );
    } else {
        checks.push([false, 'Isi focus keyword terlebih dahulu']);
    }

    $('seo-checks').innerHTML = checks.map(([ok, msg]) =>
        `<li style="color:${ok ? '#188038' : '#d93025'}">${ok ? '●' : '○'} ${msg}</li>`).join('');
}
</script>
@endpush