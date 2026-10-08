<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Str;

class SeoAnalyzer
{
    /** @return array{seo_score:int, readability_score:int} */
    public static function scores(Article $a): array
    {
        $html  = (string) $a->content;
        $text  = trim(preg_replace('/\s+/u', ' ', strip_tags($html)));
        $words = $text === '' ? 0 : count(explode(' ', $text));

        return [
            'seo_score'         => self::seo($a, $html, $words),
            'readability_score' => self::readability($html, $text, $words),
        ];
    }

    private static function seo(Article $a, string $html, int $words): int
    {
        $kw    = mb_strtolower(trim((string) $a->focus_keyword));
        $title = (string) ($a->meta_title ?: $a->title);
        $desc  = (string) ($a->meta_description ?: $a->excerpt);

        preg_match('/<p>(.*?)<\/p>/is', $html, $m);
        $firstP = mb_strtolower(strip_tags($m[1] ?? ''));

        $checks = [
            mb_strlen($title) >= 30 && mb_strlen($title) <= 60,
            mb_strlen($desc) >= 120 && mb_strlen($desc) <= 160,
            $words >= 300,
            (bool) preg_match('/<a\s[^>]*href/i', $html),
            (bool) preg_match('/<h2/i', $html),
            // 4 cek focus keyword (gagal semua kalau keyword kosong)
            $kw !== '' && str_contains(mb_strtolower($title), $kw),
            $kw !== '' && str_contains((string) $a->slug, Str::slug($kw)),
            $kw !== '' && str_contains(mb_strtolower($desc), $kw),
            $kw !== '' && str_contains($firstP, $kw),
        ];

        return (int) round(count(array_filter($checks)) / count($checks) * 100);
    }

    private static function readability(string $html, string $text, int $words): int
    {
        if ($words === 0) {
            return 0;
        }

        $sentences = preg_split('/(?<=[.!?])\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [$text];
        $counts    = array_map(fn ($s) => count(explode(' ', trim($s))), $sentences);
        $avg       = array_sum($counts) / max(count($counts), 1);
        $longPct   = count(array_filter($counts, fn ($c) => $c > 20)) / max(count($counts), 1) * 100;

        preg_match_all('/<p>(.*?)<\/p>/is', $html, $paras);
        $longPara = count(array_filter($paras[1], fn ($p) => str_word_count(strip_tags($p)) > 150));

        $checks = [
            $avg <= 20,                                 // rata-rata kalimat tidak panjang
            $longPct <= 25,                             // kalimat >20 kata maksimal 25%
            $longPara === 0,                            // tidak ada paragraf >150 kata
            $words < 300 || (bool) preg_match('/<h2/i', $html), // artikel panjang butuh subjudul
        ];

        return (int) round(count(array_filter($checks)) / count($checks) * 100);
    }
}