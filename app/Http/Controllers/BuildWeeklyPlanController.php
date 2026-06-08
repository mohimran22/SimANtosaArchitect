<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuildCategoryPlan;
use App\Models\BuildProcessItem;
use DB;

class BuildWeeklyPlanController extends Controller
{
    public function update(Request $r)
{
    $data = $r->validate([
        'project_id' => 'required|uuid', 
        'category_id' => 'required|integer', 
        'week_no' => 'required|integer', 
        'bobot' => 'required|numeric|min:0|max:100'
    ]);

    BuildCategoryPlan::updateOrCreate(
        [
            'project_id' => $data['project_id'],
            'category_order' => $data['category_id'],
            'week_no' => $data['week_no']
        ],
        [
            'bobot_percent' => $data['bobot']
        ]
    );

    return response()->json(['ok'=>true]);
}
}