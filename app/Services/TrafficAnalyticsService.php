<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use Throwable;

/**
 * Mengambil data traffic dari Google Analytics 4 (Data API)
 * dan menyiapkannya untuk kartu "Analisis Traffic" di dashboard.
 */
class TrafficAnalyticsService
{
    public const ALLOWED_RANGES = [7, 28, 90];

    public function summary(int $days = 7): array
    {
        $days = in_array($days, self::ALLOWED_RANGES, true) ? $days : 7;

        try {
            // Hanya hasil sukses yang di-cache (exception tidak ikut tersimpan).
            return Cache::remember("traffic.summary.{$days}", now()->addMinutes(30), fn () => $this->build($days));
        } catch (Throwable $e) {
            report($e);

            return ['error' => true, 'message' => 'Data traffic belum bisa diambil. Cek konfigurasi Google Analytics.'];
        }
    }

    private function build(int $days): array
    {
        $current  = Period::days($days);
        $previous = Period::create(
            Carbon::today()->subDays($days * 2 - 1),
            Carbon::today()->subDays($days)
        );

        $metrics = ['activeUsers', 'screenPageViews', 'sessions', 'averageSessionDuration'];

        $now  = Analytics::get($current, $metrics)->first() ?? [];
        $prev = Analytics::get($previous, $metrics)->first() ?? [];

        $daily = Analytics::get($current, ['activeUsers', 'screenPageViews'], ['date'])
            ->sortBy(fn ($r) => Carbon::parse($r['date'])->timestamp)
            ->values();

        $topPages = Analytics::get($current, ['screenPageViews'], ['pageTitle'], 50)
            ->sortByDesc('screenPageViews')
            ->take(10)
            ->map(fn ($r) => ['label' => $r['pageTitle'] ?: '(tanpa judul)', 'value' => (int) $r['screenPageViews']])
            ->values();

        $referrers = Analytics::get($current, ['sessions'], ['sessionSource'], 50)
            ->sortByDesc('sessions')
            ->take(10)
            ->map(fn ($r) => ['label' => $r['sessionSource'] ?: '(direct)', 'value' => (int) $r['sessions']])
            ->values();

        return [
            'error' => false,
            'days'  => $days,
            'kpi'   => [
                'visitors' => $this->kpi($now, $prev, 'activeUsers'),
                'views'    => $this->kpi($now, $prev, 'screenPageViews'),
                'sessions' => $this->kpi($now, $prev, 'sessions'),
                'duration' => $this->kpi($now, $prev, 'averageSessionDuration', true),
            ],
            'chart' => [
                'labels'   => $daily->map(fn ($r) => Carbon::parse($r['date'])->translatedFormat('d M'))->all(),
                'views'    => $daily->map(fn ($r) => (int) $r['screenPageViews'])->all(),
                'visitors' => $daily->map(fn ($r) => (int) $r['activeUsers'])->all(),
            ],
            'top_pages' => $topPages->all(),
            'referrers' => $referrers->all(),
            'updated_at' => now()->translatedFormat('d M Y H:i'),
        ];
    }

    private function kpi(array $now, array $prev, string $key, bool $isDuration = false): array
    {
        $cur = (float) ($now[$key] ?? 0);
        $old = (float) ($prev[$key] ?? 0);

        $change = $old > 0 ? round((($cur - $old) / $old) * 100, 1) : null;

        return [
            'value'  => $isDuration ? $this->duration($cur) : (int) $cur,
            'change' => $change, // null = tidak ada pembanding
        ];
    }

    private function duration(float $seconds): string
    {
        $m = intdiv((int) $seconds, 60);
        $s = (int) $seconds % 60;

        return $m > 0 ? "{$m}m {$s}s" : "{$s}s";
    }
}