<?php

namespace App\Services;

use App\Models\BuildProcessItem;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class BuildProcessSyncService
{
    /*
    |--------------------------------------------------------------------------
    | SAFE AUTO SYNC
    |--------------------------------------------------------------------------
    */

    public function syncLight(Project $project): void
    {
        $project->load(
            'offer.rab.categories.uraians.items'
        );

        foreach ($project->offer->rab->categories as $cIndex => $category) {

            foreach ($category->uraians as $uIndex => $uraian) {

                foreach ($uraian->items as $iIndex => $item) {

                    BuildProcessItem::updateOrCreate(

                        [
                            'project_id' => $project->id,
                            'rab_item_id' => $item->id,
                        ],

                        [
                            'category_name' => $category->name,
                            'uraian_name' => $uraian->name,

                            'job_category_id' => $item->job_category_id,
                            'uraian' => $item->job_name,

                            'price' => $item->price,
                            'volume' => $item->volume,
                            'total' => $item->total,
                            'satuan' => $item->satuan,

                            'category_order' => $cIndex,
                            'uraian_order' => $uIndex,
                            'item_order' => $iIndex,
                        ]
                    );
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FULL MANUAL SYNC
    |--------------------------------------------------------------------------
    */

    public function syncFull(Project $project): void
    {
        DB::transaction(function () use ($project) {

            $project->load(
                'offer.rab.categories.uraians.items'
            );

            /*
            |--------------------------------------------------------------------------
            | Ambil semua rab item terbaru
            |--------------------------------------------------------------------------
            */

            $rabItems = collect();

            foreach ($project->offer->rab->categories as $cIndex => $category) {

                foreach ($category->uraians as $uIndex => $uraian) {

                    foreach ($uraian->items as $iIndex => $item) {

                        $rabItems->push([

                            'rab_item_id' => $item->id,

                            'category_name' => $category->name,
                            'uraian_name' => $uraian->name,

                            'job_category_id' => $item->job_category_id,
                            'uraian' => $item->job_name,

                            'price' => $item->price,
                            'volume' => $item->volume,
                            'total' => $item->total,
                            'satuan' => $item->satuan,

                            'category_order' => $cIndex,
                            'uraian_order' => $uIndex,
                            'item_order' => $iIndex,
                        ]);
                    }
                }
            }

            $currentRabIds = $rabItems
                ->pluck('rab_item_id')
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | Existing build items
            |--------------------------------------------------------------------------
            */

            $existingBuildItems = BuildProcessItem::where(
                'project_id',
                $project->id
            )
            ->whereNull('parent_id')
            ->get();

            /*
            |--------------------------------------------------------------------------
            | DELETE ITEM YANG SUDAH HILANG DI RAB
            |--------------------------------------------------------------------------
            */

            foreach ($existingBuildItems as $buildItem) {

                $hasProgress =
                    $buildItem->weeklyProgresses()->exists();

                $stillExists =
                    in_array(
                        $buildItem->rab_item_id,
                        $currentRabIds
                    );

                /*
                |--------------------------------------------------------------------------
                | Jangan hapus item yang punya progress
                |--------------------------------------------------------------------------
                */

                if (!$stillExists && !$hasProgress) {

                    $buildItem->delete();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE / UPDATE
            |--------------------------------------------------------------------------
            */

            foreach ($rabItems as $row) {

                $buildItem = BuildProcessItem::firstOrNew([

                    'project_id' => $project->id,
                    'rab_item_id' => $row['rab_item_id'],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Protect item yang sudah punya progress
                |--------------------------------------------------------------------------
                */

                $hasProgress =
                    $buildItem->exists
                    &&
                    $buildItem->weeklyProgresses()->exists();

                /*
                |--------------------------------------------------------------------------
                | Kalau sudah ada progress:
                | update ringan saja
                |--------------------------------------------------------------------------
                */

                if ($hasProgress) {

                    $buildItem->update([

                        'category_name' => $row['category_name'],
                        'uraian_name' => $row['uraian_name'],

                        'uraian' => $row['uraian'],

                        'price' => $row['price'],

                        /*
                        |--------------------------------------------------------------------------
                        | volume jangan disentuh
                        |--------------------------------------------------------------------------
                        */

                        // 'volume' => $row['volume'],

                        'satuan' => $row['satuan'],

                        'category_order' => $row['category_order'],
                        'uraian_order' => $row['uraian_order'],
                        'item_order' => $row['item_order'],
                    ]);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Kalau belum ada progress:
                | boleh full update
                |--------------------------------------------------------------------------
                */

                $buildItem->fill([

                    'project_id' => $project->id,

                    'category_name' => $row['category_name'],
                    'uraian_name' => $row['uraian_name'],

                    'job_category_id' => $row['job_category_id'],
                    'uraian' => $row['uraian'],

                    'price' => $row['price'],
                    'volume' => $row['volume'],
                    'total' => $row['total'],
                    'satuan' => $row['satuan'],

                    'category_order' => $row['category_order'],
                    'uraian_order' => $row['uraian_order'],
                    'item_order' => $row['item_order'],
                ]);

                $buildItem->save();
            }
        });
    }
}