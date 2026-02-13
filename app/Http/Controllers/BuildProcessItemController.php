<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\BuildProcessItem;
use DB;

class BuildProcessItemController extends Controller
{
public function updateBobot(Request $request)
{
    $project = Project::findOrFail($request->input('project_id'));

    if ($project->bobot_locked) {
        return response()->json([
            'ok'=>false,
            'message'=>'Bobot sudah dikunci'
        ],423);
    }

    $items = $request->input('items', []);
    $total = collect($items)->sum('bobot');

    if (round($total,2) != 100) {
        return response()->json([
            'ok'=>false,
            'message'=>'Total bobot harus 100%'
        ],422);
    }

    DB::transaction(function() use ($items,$project){

        foreach ($items as $row) {
            BuildProcessItem::where('id',$row['id'])
                ->update([
                    'bobot_percent' => $row['bobot']
                ]);
        }

        // 🔒 LOCK
        $project->update([
            'bobot_locked' => true
        ]);
    });

    return response()->json([
        'ok'=>true,
        'locked'=>true
    ]);
}
}
