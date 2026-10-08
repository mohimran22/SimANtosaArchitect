<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
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

        Article::create($data);

        return redirect()->route('articles.index')->with('success', 'Artikel disimpan.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $article->id);
        $data = $this->handleFiles($request, $data, $article);

        $article->update($data);

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
        ]);

        $data['content'] = clean($data['content']);          // sanitasi HTML (mews/purifier)
        $data['noindex'] = $request->boolean('noindex');

        if ($data['status'] === 'published') {
            $data['published_at'] = $request->input('published_at') ?: now();
        } else {
            $data['published_at'] = null;
        }

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
}