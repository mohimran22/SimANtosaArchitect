<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\BuildWeeklyProgress;
use App\Models\BuildProcessItem;
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

    $totalCols = 6 + ($filteredWeeks->count() * 3);

    $rekap = collect($groupedItems)->map(function ($cat) use ($filteredWeeks) {

        $items = collect($cat['uraians'])
            ->flatMap(fn($u) => $u['items']);

        $bobot = $items->sum('bobot_percent');

        $realisasi = $items->sum(function ($item) use ($filteredWeeks) {

            return collect($filteredWeeks)
                ->sum(function ($w) use ($item) {

                    $prog = $item->progress_map[$w['week_no']] ?? null;

                    $volMinggu = $prog->volume ?? 0;

                    return $item->volume > 0
                        ? ($volMinggu / $item->volume)
                            * $item->bobot_percent
                        : 0;


                });

        });

        return [
            'category' => $cat['category_name'],
            'bobot' => $bobot,
            'realisasi' => $realisasi,
        ];

    });

    $pdf = Pdf::loadView(
        'build.pdf',
        [
            'project' => $project,
            'groupedItems' => $groupedItems,
            'weeks' => $filteredWeeks,
            'totalCols' => $totalCols,
            'rekap' => $rekap,
        ]
    );

    $pdf->setPaper('a4', 'potrait');

    return $pdf->stream(
        'Laporan Mingguan.pdf'
    );
}

private function groupItems($buildItems)
{
    return $buildItems
        ->groupBy('category_name')
        ->map(function ($categoryItems, $categoryName) {

            return [
                'category_name' => $categoryName,

                'uraians' => $categoryItems
                    ->groupBy('uraian_name')
                    ->map(function ($uraianItems, $uraianName) {

                        return [
                            'uraian_name' => $uraianName,

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