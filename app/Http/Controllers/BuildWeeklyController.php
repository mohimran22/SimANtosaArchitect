<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuildWeeklyProgress;
use App\Models\BuildProcessItem;
use DB;

class BuildWeeklyController extends Controller
{
public function update(Request $r)
{
    $item = BuildProcessItem::findOrFail($r->item_id);

    $progress = $item->volume > 0
        ? ($r->volume / $item->volume) * 100
        : 0;

    $bobot = $progress * $item->bobot_percent / 100;

    BuildWeeklyProgress::updateOrCreate(
        [
            'build_process_item_id'=>$item->id,
            'week_no'=>$r->week_no
        ],
        [
            'volume'=>$r->volume,
            'progress_percent'=>$progress,
            'bobot_percent'=>$bobot
        ]
    );

    return response()->json(['ok'=>true]);
}
}

