@extends('tablar::page')

@section('content')
@php $isEdit = $article->exists; @endphp

<style>
    .ck-editor__editable { min-height: 380px; }
    #seo-checks li { padding: 2px 0; font-size: 14px; }
    .serp { font-family: Arial, sans-serif; max-width: 600px; }
</style>

<form method="POST" enctype="multipart/form-data"
      action="{{ $isEdit ? route('articles.update', $article) : route('articles.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col"><h2 class="page-title">{{ $isEdit ? 'Edit' : 'Tulis' }} Artikel</h2></div>
                <div class="col-auto ms-auto d-flex gap-2">
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-dark">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-3">

                {{-- KOLOM KIRI --}}
                <div class="col-lg-8">

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Judul</label>
                                <input type="text" id="title" name="title" class="form-control"
                                       value="{{ old('title', $article->title) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug <span class="text-muted">(kosongkan untuk otomatis)</span></label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                       value="{{ old('slug', $article->slug) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Konten</label>
                                <textarea id="content" name="content">{{ old('content', $article->content) }}</textarea>
                            </div>

                            <div>
                                <label class="form-label">Ringkasan (excerpt)</label>
                                <textarea id="excerpt" name="excerpt" rows="3" maxlength="300"
                                          class="form-control">{{ old('excerpt', $article->excerpt) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="card mb-3">
                        <div class="card-header"><h3 class="card-title">SEO</h3></div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Focus keyword</label>
                                <input type="text" id="focus_keyword" name="focus_keyword" class="form-control"
                                       value="{{ old('focus_keyword', $article->focus_keyword) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Meta title <small id="mt-count" class="text-muted"></small></label>
                                <input type="text" id="meta_title" name="meta_title" maxlength="70" class="form-control"
                                       value="{{ old('meta_title', $article->meta_title) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Meta description <small id="md-count" class="text-muted"></small></label>
                                <textarea id="meta_description" name="meta_description" maxlength="170" rows="3"
                                          class="form-control">{{ old('meta_description', $article->meta_description) }}</textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Canonical URL <span class="text-muted">(opsional)</span></label>
                                    <input type="url" name="canonical_url" class="form-control"
                                           value="{{ old('canonical_url', $article->canonical_url) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gambar share / OG image <span class="text-muted">(opsional)</span></label>
                                    <input type="file" name="og_image" accept="image/*" class="form-control">
                                </div>
                            </div>

                            <label class="form-check mb-4">
                                <input type="checkbox" name="noindex" value="1" class="form-check-input"
                                       @checked(old('noindex', $article->noindex))>
                                <span class="form-check-label">Jangan diindex Google (noindex)</span>
                            </label>

                            <div class="form-label">Preview hasil Google</div>
                            <div class="serp border rounded p-3 mb-4 bg-white">
                                <div id="pv-url" style="color:#188038;font-size:13px"></div>
                                <div id="pv-title" style="color:#1a0dab;font-size:19px"></div>
                                <div id="pv-desc" style="color:#4d5156;font-size:13px"></div>
                            </div>

                            <div class="form-label">Analisis</div>
                            <ul id="seo-checks" class="list-unstyled mb-0"></ul>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="col-lg-4">

                    <div class="card mb-3">
                        <div class="card-header"><h3 class="card-title">Publikasi</h3></div>
                        <div class="card-body">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select mb-3">
                                <option value="draft" @selected(old('status', $article->status) === 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $article->status) === 'published')>Terbit</option>
                            </select>
                            <button type="submit" class="btn btn-dark w-100">Simpan</button>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header"><h3 class="card-title">Gambar utama</h3></div>
                        <div class="card-body">
                            @if($article->featured_image)
                                <img src="{{ asset('storage/'.$article->featured_image) }}" alt=""
                                     class="img-fluid rounded mb-2">
                            @endif
                            <input type="file" name="featured_image" accept="image/*" class="form-control">
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header"><h3 class="card-title">Kategori</h3></div>
                        <div class="card-body">
                            @php
                                $allCategories = \App\Models\Category::orderBy('name')->get();
                                $selectedCats  = old('categories', $article->categories->pluck('id')->all());
                            @endphp
                            @forelse($allCategories as $c)
                                <label class="form-check">
                                    <input type="checkbox" name="categories[]" value="{{ $c->id }}"
                                           class="form-check-input" @checked(in_array($c->id, $selectedCats))>
                                    <span class="form-check-label">{{ $c->name }}</span>
                                </label>
                            @empty
                                <div class="text-muted">Belum ada kategori.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header"><h3 class="card-title">Tag</h3></div>
                        <div class="card-body">
                            <input type="text" name="tags" class="form-control"
                                   placeholder="pisahkan dengan koma"
                                   value="{{ old('tags', $article->tags->pluck('name')->implode(', ')) }}">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('js')
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