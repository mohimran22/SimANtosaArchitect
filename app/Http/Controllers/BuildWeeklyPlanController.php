<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuildWeeklyPlan;
use App\Models\BuildProcessItem;
use DB;

class BuildWeeklyPlanController extends Controller
{
    public function update(Request $r)
{
    $data = $r->validate([
        'project_id' => 'required|uuid',
        'week_no' => 'required|integer',
        'bobot' => 'required|numeric|min:0|max:100'
    ]);

    BuildWeeklyPlan::updateOrCreate(
        [
            'project_id' => $data['project_id'],
            'week_no' => $data['week_no']
        ],
        [
            'bobot_percent' => $data['bobot']
        ]
    );

    return response()->json(['ok'=>true]);
}
}