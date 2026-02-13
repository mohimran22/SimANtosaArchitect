<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuildProcessItem;

class BuildProcessItemController extends Controller
{
    public function updateBobot(Request $request)
    {
        $items = $request->items ?? [];

        $total = collect($items)->sum('bobot');

        if (round($total,2) != 100) {
            return response()->json([
                'ok' => false,
                'message' => 'Total bobot harus 100%',
                'total' => $total
            ], 422);
        }

        foreach ($items as $row) {
            BuildProcessItem::where('id',$row['id'])
                ->update([
                    'bobot_percent' => $row['bobot']
                ]);
        }

        return response()->json([
            'ok' => true,
            'total' => $total
        ]);
    }
}
