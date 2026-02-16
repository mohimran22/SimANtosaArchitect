<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuildWeeklyProgress;
use App\Models\BuildProcessItem;
use DB;

class BuildWeeklyController extends Controller
{
// public function update(Request $r)
// {
//     $item = BuildProcessItem::findOrFail($r->item_id);

//     $vol = floatval($r->volume ?? 0);

//     $progress = $item->volume > 0
//         ? ($vol / $item->volume) * 100
//         : 0;

//     $bobot = $progress * $item->bobot_percent / 100;

//     BuildWeeklyProgress::updateOrCreate(
//         [
//             'build_process_item_id'=>$item->id,
//             'week_no'=>$r->week_no
//         ],
//         [
//             'volume'=>$vol,              
//             'progress_percent'=>$progress,
//             'bobot_percent'=>$bobot
//         ]
//     );

//     return response()->json(['ok'=>true]);
// }
public function update(Request $r)
{
    $item = BuildProcessItem::findOrFail($r->item_id);

    $vol = floatval($r->volume ?? 0);

    $totalExisting = BuildWeeklyProgress::where(
            'build_process_item_id', $item->id
        )
        ->where('week_no', '!=', $r->week_no)
        ->sum('volume');

    $sisa = $item->volume - $totalExisting;

    if ($sisa <= 0) {
        return response()->json([
            'error' => 'Volume sudah tercapai'
        ], 422);
    }

    if ($vol > $sisa) {
        return response()->json([
            'error' => 'Melebihi volume kontrak'
        ], 422);
    }

    $progress = $item->volume > 0
        ? ($vol / $item->volume) * 100
        : 0;

    $bobot = $progress * $item->bobot_percent / 100;

    BuildWeeklyProgress::updateOrCreate(
        [
            'build_process_item_id'=>$item->id,
            'week_no'=>$r->week_no
        ],
        [
            'weekly_report_id'=>$r->weekly_report_id,
            'volume'=>$vol,
            'progress_percent'=>$progress,
            'bobot_percent'=>$bobot
        ]
    );

    return response()->json([
        'ok'=>true,
        'full' => ($totalExisting + $vol) >= $item->volume
    ]);
}
}