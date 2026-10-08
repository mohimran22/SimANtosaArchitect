<?php

namespace App\Http\Controllers;

use App\Models\Article;

class SitemapController extends Controller
{
    public function index()
    {
        $articles = Article::published()->where('noindex', false)->latest('updated_at')->get();

        return response()
            ->view('sitemap', compact('articles'))
            ->header('Content-Type', 'application/xml');
    }
}