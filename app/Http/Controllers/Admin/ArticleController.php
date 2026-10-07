<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(15);
        return view('admin.articles.index', compact('articles'));
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