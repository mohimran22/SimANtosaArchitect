<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Tag;
use App\Services\SeoAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $status  = $request->query('status');
        $orderby = in_array($request->query('orderby'), ['title', 'created_at']) ? $request->query('orderby') : 'created_at';
        $order   = $request->query('order') === 'asc' ? 'asc' : 'desc';

        $query = Article::query()->with(['author', 'categories', 'tags']);

        if ($status === 'trash') {
            $query->onlyTrashed();
        } elseif (in_array($status, ['published', 'draft'])) {
            $query->where('status', $status);
        }

        $query
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->month, fn ($q, $m) => $q
                ->whereYear('created_at', substr($m, 0, 4))
                ->whereMonth('created_at', substr($m, 5, 2)))
            ->when($request->category, fn ($q, $c) => $q
                ->whereHas('categories', fn ($x) => $x->where('categories.id', $c)))
            ->when($request->seo, fn ($q, $v) => $this->scoreFilter($q, 'seo_score', $v))
            ->when($request->readability, fn ($q, $v) => $this->scoreFilter($q, 'readability_score', $v));

        $articles = $query->orderBy($orderby, $order)->paginate(20)->withQueryString();

        $counts = [
            'all'       => Article::count(),
            'published' => Article::where('status', 'published')->count(),
            'draft'     => Article::where('status', 'draft')->count(),
            'trash'     => Article::onlyTrashed()->count(),
        ];

        $months = Article::withTrashed()->orderByDesc('created_at')->pluck('created_at')
            ->map(fn ($d) => $d->format('Y-m'))->unique()->values();

        $categories = \App\Models\Category::orderBy('name')->get();

        return view('admin.articles.index', compact(
            'articles', 'counts', 'months', 'categories', 'status', 'orderby', 'order'
        ));
    }

    public function create()
    {
        return view('admin.articles.form', ['article' => new Article()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['author_id'] = $request->user()->id;
        $data['slug']      = $this->uniqueSlug($data['slug'] ?: $data['title']);
        $data = $this->handleFiles($request, $data);

        $article = Article::create($data);
        $this->afterSave($request, $article);
        return redirect()->route('articles.index')->with('success', 'Artikel disimpan.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $this->validated($request, $article);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $article->id);
        $data = $this->handleFiles($request, $data, $article);

        $article->update($data);
        $this->afterSave($request, $article);

        return redirect()->route('articles.index')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return back()->with('success', 'Artikel dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255',
            'content'          => 'required|string',
            'excerpt'          => 'nullable|string|max:300',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:170',
            'focus_keyword'    => 'nullable|string|max:100',
            'canonical_url'    => 'nullable|url|max:255',
            'featured_image'   => 'nullable|image|max:2048',
            'og_image'         => 'nullable|image|max:2048',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags'         => 'nullable|string|max:500',
        ]);

        $data['content'] = clean($data['content']);          // sanitasi HTML (mews/purifier)
        $data['noindex'] = $request->boolean('noindex');

        if ($data['status'] === 'published') {
            $data['published_at'] = $request->input('published_at') ?: ($article?->published_at ?? now());
        } else {
            $data['published_at'] = null;
        }
        unset($data['categories'], $data['tags']);
        return $data;
    }

    private function handleFiles(Request $request, array $data, ?Article $article = null): array
    {
        foreach (['featured_image', 'og_image'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('articles', 'public');
            } else {
                unset($data[$field]); // jangan timpa gambar lama dengan null
            }
        }
        return $data;
    }

    private function uniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = Str::slug($text);
        $slug = $base;
        $i = 2;

        while (Article::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function scoreFilter($q, string $col, string $v)
{
    return match ($v) {
        'good' => $q->where($col, '>=', 70),
        'ok'   => $q->whereBetween($col, [40, 69]),
        'bad'  => $q->where($col, '<', 40),
        'none' => $q->whereNull($col),
        default => $q,
    };
}

public function bulk(Request $request)
{
    $request->validate([
        'bulk_action' => 'required|in:publish,draft,trash,restore,delete',
        'ids'         => 'required|array',
        'ids.*'       => 'integer',
    ]);

    $articles = Article::withTrashed()->whereIn('id', $request->ids)->get();

    foreach ($articles as $a) {
        match ($request->bulk_action) {
            'publish' => $a->update(['status' => 'published', 'published_at' => $a->published_at ?? now()]),
            'draft'   => $a->update(['status' => 'draft']),
            'trash'   => $a->delete(),
            'restore' => $a->restore(),
            'delete'  => $a->forceDelete(),
        };
    }

    return back()->with('success', count($articles) . ' artikel diproses.');
}

private function afterSave(Request $request, Article $article): void
{
    $article->categories()->sync($request->input('categories', []));

    $tagIds = collect(explode(',', (string) $request->input('tags')))
        ->map(fn ($t) => trim($t))->filter()->unique()
        ->map(fn ($name) => Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name])->id);

    $article->tags()->sync($tagIds);
    $article->update(SeoAnalyzer::scores($article->fresh()));
}
}