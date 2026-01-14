<?php

namespace App\Services;

use App\Models\JobCategory;
use App\Models\JobCategoryItem;
use App\Models\LaborCost;
use App\Models\EquipmentCost;
use App\Models\ProductSupplier;
use Illuminate\Support\Facades\DB;

class RabRecalculator
{
    public static function recalcCategory(JobCategory $category)
{
    $items = $category->items;

    $totalLabor = $items->where('category', 'labor')->sum('total_price');
    $totalProduct = $items->where('category', 'product')->sum('total_price');
    $totalEquipment = $items->where('category', 'equipment')->sum('total_price');

    $subTotal = $totalLabor + $totalProduct + $totalEquipment;

    $overheadValue = $subTotal * ($category->overhead_percent / 100);
    $profitValue   = $subTotal * ($category->profit_percent / 100);
    $grandTotal    = $subTotal + $overheadValue + $profitValue;

    $category->update([
        'subtotal' => $subTotal,
        'overhead_value' => $overheadValue,
        'profit_value' => $profitValue,
        'grand_total' => $grandTotal,
    ]);
}
    public static function recalcItem(JobCategoryItem $item)
    {
        $price = null;
        
        if ($item->labor_cost_id) {
            $price = LaborCost::where('id', $item->labor_cost_id)->value('base_unit_price');
        }
        elseif ($item->equipment_cost_id) {
            $price = EquipmentCost::where('id', $item->equipment_cost_id)->value('base_unit_price');
        }
        elseif ($item->product_id) {
            $price = ProductSupplier::where('product_id', $item->product_id)
                ->orderBy('selling_prices')
                ->value('selling_prices');
        }

        if ($price === null) {
            $price = $item->base_unit_price;
        }


        $item->update([
            'base_unit_price' => $price,
            'total_price' => $item->coefisien * $price,
        ]);
    }

    public static function recalcItemAndParent(JobCategoryItem $item)
{
    self::recalcItem($item);
    self::recalcCategory($item->jobCategory);
}


public static function recalcByLabor($laborId)
{
    JobCategoryItem::where('labor_cost_id', $laborId)
        ->with('jobCategory')
        ->get()
        ->groupBy('job_category_id')
        ->each(function ($items) {
            foreach ($items as $item) {
                self::recalcItem($item);
            }
            self::recalcCategory($items->first()->jobCategory);
        });
}

public static function recalcByEquipment($laborId)
{
    JobCategoryItem::where('equipment_cost_id', $laborId)
        ->with('jobCategory')
        ->get()
        ->groupBy('job_category_id')
        ->each(function ($items) {
            foreach ($items as $item) {
                self::recalcItem($item);
            }
            self::recalcCategory($items->first()->jobCategory);
        });
}


public static function recalcByproduct($laborId)
{
    JobCategoryItem::where('product_id', $laborId)
        ->with('jobCategory')
        ->get()
        ->groupBy('job_category_id')
        ->each(function ($items) {
            foreach ($items as $item) {
                self::recalcItem($item);
            }
            self::recalcCategory($items->first()->jobCategory);
        });
}

    public static function recalcAll()
    {
        JobCategoryItem::chunk(500, function ($items) {
            foreach ($items as $item) {
                self::recalcItem($item);
            }
        });
    }
}
