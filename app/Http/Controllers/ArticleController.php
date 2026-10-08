<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Artikel');
        SEOMeta::setDescription('Artikel dan wawasan seputar arsitektur dari Antosa Architect.');
        SEOMeta::setCanonical(url()->current());

        $articles = Article::published()->latest('published_at')->paginate(9);
        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        abort_unless(
            $article->status === 'published' && $article->published_at <= now(),
            404
        );

        $title = $article->meta_title ?: $article->title;
        $desc  = $article->meta_description
            ?: Str::limit(strip_tags($article->excerpt ?: $article->content), 160);
        $img   = $article->og_image ?: $article->featured_image;
        $imgUrl = $img ? asset('storage/' . $img) : null;
        $canonical = $article->canonical_url ?: route('articles.show', $article);

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($desc);
        SEOMeta::setCanonical($canonical);
        if ($article->noindex) {
            SEOMeta::setRobots('noindex, nofollow');
        }

        OpenGraph::setTitle($title)->setDescription($desc)->setUrl($canonical)
            ->setType('article')->setSiteName('Antosa Architect');
        TwitterCard::setTitle($title)->setDescription($desc);
        JsonLd::setType('Article')->setTitle($title)->setDescription($desc)->setUrl($canonical);
        JsonLd::addValue('datePublished', $article->published_at->toIso8601String());
        JsonLd::addValue('dateModified', $article->updated_at->toIso8601String());
        JsonLd::addValue('author', ['@type' => 'Person', 'name' => $article->author->name]);

        if ($imgUrl) {
            OpenGraph::addImage($imgUrl);
            TwitterCard::setImage($imgUrl);
            JsonLd::addImage($imgUrl);
        }

        return view('articles.show', compact('article'));
    }
}
