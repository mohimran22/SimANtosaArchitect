<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Project;
use App\Models\BuildWeeklyProgress;
use App\Models\BuildProcessItem;
use App\Models\BuildCategoryPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;

class BuildWeeklyController extends Controller
{
public function update(Request $r)
{

    $item = BuildProcessItem::findOrFail($r->item_id);

    $progress = BuildWeeklyProgress::firstOrCreate([
        'build_process_item_id' => $item->id,
        'week_no' => $r->week_no
    ], [
        'volume' => 0,
        'progress_percent' => 0,
        'bobot_percent' => 0,
        'just_kurang' => 0,
        'just_tambah' => 0,
        'just_baru' => 0,
    ]);

    if ($r->has('volume')) {

        $vol = floatval($r->volume);

        if (!$item->is_tambahan) {
            $totalExisting = BuildWeeklyProgress::where(
                    'build_process_item_id', $item->id
                )
                ->where('week_no', '!=', $r->week_no)
                ->sum('volume');

            $sisa = $item->volume - $totalExisting;

            if ($sisa <= 0) {
                return response()->json([
                    'error' => 'Volume kontrak sudah tercapai'
                ], 422);
            }

            if ($vol > $sisa) {
                return response()->json([
                    'error' => 'Melebihi volume kontrak'
                ], 422);
            }
        }

        $progressPercent = $item->volume > 0
            ? ($vol / $item->volume) * 100
            : 0;

        $bobot = $progressPercent * $item->bobot_percent / 100;

        $progress->volume = $vol;
        $progress->progress_percent = $progressPercent;
        $progress->bobot_percent = $bobot;
    }

    if ($r->has('just_kurang')) {
        $progress->just_kurang = floatval($r->just_kurang ?? 0);
    }

    if ($r->has('just_tambah')) {
        $progress->just_tambah = floatval($r->just_tambah ?? 0);
    }

    if ($r->has('just_baru')) {
        $progress->just_baru = floatval($r->just_baru ?? 0);
    }

    $progress->updated_at = now();
    $progress->save();
    $project = $item->project;

    $totalProgress = BuildWeeklyProgress::whereHas('item', function($q) use ($project){
            $q->where('project_id',$project->id);
        })
        ->sum('bobot_percent');

    InvoiceBuildController::autoGenerate(
        $project,
        $totalProgress
    );
    app(\App\Services\BuildInvoiceService::class)
    ->generateJustek($project);
    return response()->json([
        'ok' => true,
        'item_id' => $item->id,
        'week_no' => $r->week_no
    ]);
}

public function exportPdf(Request $request, Project $project)
{
    $week = $request->week;
    $date = $request->date;
    
    $buildItems = BuildProcessItem::with([
        'weeklyProgresses',
        'tambahan',
    ])
    ->where('project_id', $project->id)
    ->orderBy('category_order')
    ->orderBy('uraian_order')
    ->orderBy('item_order')
    ->get();

    $buildItems->each(function ($item) { $item->progress_map = $item->weeklyProgresses ->keyBy('week_no'); });

    $groupedItems = $this->groupItems($buildItems);

    $filteredWeeks = collect($project->week_labels);

    if ($week) {

        $filteredWeeks = $filteredWeeks
            ->where('week_no', $week);

    }

    if ($date) {

        $filteredWeeks = $filteredWeeks
            ->filter(function ($w) use ($date) {

                $start = Carbon::createFromFormat(
                    'd/m/Y',
                    $w['start']
                );

                $end = Carbon::createFromFormat(
                    'd/m/Y',
                    $w['end']
                );

                return Carbon::parse($date)
                    ->between($start, $end);

            });

    }

    $totalCols = 5 + ($filteredWeeks->count() * 9);


$rekap = collect($groupedItems)->map(function ($cat) use ($filteredWeeks, $project, $week) {

    $items = collect($cat['uraians'])
        ->flatMap(fn($u) => $u['items']);

    // Bobot kontrak kategori
    $bobot = $items->sum('bobot_percent');

    // category id
    $categoryId =
        $items->first()->category_order;

    // minggu aktif
    $weekNow =
        $filteredWeeks->max('week_no');

    // minggu lalu
    $weekPrev =
        max($weekNow - 1, 0);

    $rencana = BuildCategoryPlan::query()

        ->where('project_id', $project->id)

        ->where('category_order', $categoryId)

        ->where('week_no', '<=', $weekNow)

        ->sum('bobot_percent');

    $prestasiLalu = $items->avg(function ($item)
        use ($weekPrev) {

            $vol = 0;

            for($w = 1; $w <= $weekPrev; $w++){

                $prog =
                    $item->progress_map[$w]
                    ?? null;

                $vol +=
                    $prog->volume ?? 0;

            }

            return $item->volume > 0

                ? ($vol / $item->volume) * 100

                : 0;
        });

    $bobotLalu = $items->sum(function ($item)
    use ($weekPrev) {

        $sum = 0;

        for($w = 1; $w <= $weekPrev; $w++){

            $prog =
                $item->progress_map[$w]
                ?? null;

            $vol =
                $prog->volume ?? 0;

            $sum += $item->volume > 0

                ? ($vol / $item->volume)
                    * $item->bobot_percent

                : 0;

        }

        return $sum;
    });
    
    $prestasiMingguIni = $items->avg(function ($item) use ($weekNow) {

        $prog =
            $item->progress_map[$weekNow]
            ?? null;

        $vol =
            $prog->volume ?? 0;

        return $item->volume > 0

            ? ($vol / $item->volume) * 100

            : 0;

    });
    $bobotMingguIni = $items->sum(function ($item) use ($weekNow) {

        $prog = $item->progress_map[$weekNow] ?? null;

        $vol = $prog->volume ?? 0;

        return $item->volume > 0

            ? ($vol / $item->volume)
                * $item->bobot_percent

            : 0;

    });
    $prestasiKumulatif = $prestasiLalu + $prestasiMingguIni;

    $realisasiKumulatif = $bobotLalu + $bobotMingguIni;

    return [

        'category' => $cat['category_name'],

        // kontrak
        'bobot' => $bobot,

        // plan
        'rencana' => $rencana,

        // SD minggu lalu
        'prestasi_lalu' => $prestasiLalu,
        'bobot_lalu' => $bobotLalu,

        // minggu ini
        'prestasi_minggu_ini' => $prestasiMingguIni,

        'bobot_minggu_ini' => $bobotMingguIni,

        // SD minggu ini
        'prestasi_sd_minggu_ini' => $prestasiKumulatif,

        'realisasi_sd_minggu_ini' => $realisasiKumulatif,
    ];

});
    $kurvaS = $project->getKurvaSData();

$labels = collect($kurvaS)
    ->pluck('week')
    ->map(fn($w) => 'M'.$w)
    ->values();

$realisasi = collect($kurvaS)
    ->pluck('progress')
    ->values();
$kurvaS = $project->getKurvaSData() ?? [];
$labels = collect($kurvaS)
    ->pluck('week')
    ->map(fn($w) => 'M'.$w)
    ->values()
    ->toArray();

$realisasi = collect($kurvaS)
    ->pluck('progress')
    ->values()
    ->toArray();
$plan = [];

$jalan = 0;

foreach($project->week_labels as $w){

    $mingguan =
        \App\Models\BuildCategoryPlan::query()

        ->where('project_id', $project->id)

        ->where('week_no', $w['week_no'])

        ->sum('bobot_percent');

    $jalan += $mingguan;

    $plan[] = round($jalan, 2);

}
$chartConfig = [

    'type' => 'line',

    'data' => [

        'labels' => $labels,

        'datasets' => [

            [
                'label' => 'Rencana',
                'data' => $plan,
            ],

            [
                'label' => 'Realisasi',
                'data' => $realisasi,
            ]

        ]

    ]

];

$chartUrl =
    'https://quickchart.io/chart?c=' .
    urlencode(json_encode($chartConfig));

    $pdf = Pdf::loadView(
        'build.pdf',
        [
            'project' => $project,
            'groupedItems' => $groupedItems,
            'weeks' => $filteredWeeks,
            'totalCols' => $totalCols,
            'rekap' => $rekap,
            'chartUrl' => $chartUrl,
        ]
    );

    $pdf->setPaper('a4', 'potrait');

    return $pdf->stream('LAPORAN MINGGUAN-'.$project->project_name.'.pdf');
}

private function groupItems($buildItems)
{
    return $buildItems
        ->groupBy('category_order')
        ->map(function ($categoryItems) {

            $firstCategory = $categoryItems->first();

            return [

                'category_name' => $firstCategory->category_name,
                'category_order' => $firstCategory->category_order,

                'uraians' => $categoryItems
                    ->groupBy('uraian_order')
                    ->map(function ($uraianItems) {

                        $firstUraian = $uraianItems->first();

                        return [

                            'uraian_name' => $firstUraian->uraian_name,
                            'uraian_order' => $firstUraian->uraian_order,

                            'items' => $uraianItems
                                ->sortBy('item_order')
                                ->values(),

                        ];

                    })->values()

            ];

        })->values();
}



//     public function update(Request $r)
// {
//     $item = BuildProcessItem::findOrFail($r->item_id);

//     $progress = BuildWeeklyProgress::firstOrNew([
//         'build_process_item_id' => $item->id,
//         'week_no' => $r->week_no
//     ]);

//     if (!$progress->exists) {
//         $progress->volume = 0;
//         $progress->progress_percent = 0;
//         $progress->bobot_percent = 0;
//         $progress->just_kurang = 0;
//         $progress->just_tambah = 0;
//         $progress->just_baru = 0;
//     }

//     if ($r->has('volume')) {

//         $vol = floatval($r->volume);

//         $totalExisting = BuildWeeklyProgress::where(
//                 'build_process_item_id', $item->id
//             )
//             ->where('week_no', '!=', $r->week_no)
//             ->sum('volume');

//         $sisa = $item->volume - $totalExisting;

//         if ($sisa <= 0) {
//             return response()->json(['error'=>'Volume sudah tercapai'],422);
//         }

//         if ($vol > $sisa) {
//             return response()->json(['error'=>'Melebihi volume kontrak'],422);
//         }

//         $progressPercent = $item->volume > 0
//             ? ($vol / $item->volume) * 100
//             : 0;

//         $bobot = $progressPercent * $item->bobot_percent / 100;

//         $progress->volume = $vol;
//         $progress->progress_percent = $progressPercent;
//         $progress->bobot_percent = $bobot;
//     }

//     if ($r->has('just_kurang')) {
//         $progress->just_kurang = floatval($r->just_kurang);
//     }

//     if ($r->has('just_tambah')) {
//         $progress->just_tambah = floatval($r->just_tambah);
//     }

//     if ($r->has('just_baru')) {
//         $progress->just_baru = floatval($r->just_baru);
//     }

//     $progress->save();

//     return response()->json(['ok'=>true]);
// }

}