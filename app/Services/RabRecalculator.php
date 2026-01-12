<?php

namespace App\Services;

use App\Models\JobCategoryItem;
use App\Models\LaborCost;
use App\Models\EquipmentCost;
use App\Models\ProductSupplier;
use Illuminate\Support\Facades\DB;

class RabRecalculator
{
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

    public static function recalcByLabor($laborId)
    {
        JobCategoryItem::where('labor_cost_id', $laborId)->each(fn($item) => self::recalcItem($item));
    }

    public static function recalcByEquipment($equipmentId)
    {
        JobCategoryItem::where('equipment_cost_id', $equipmentId)->each(fn($item) => self::recalcItem($item));
    }

    public static function recalcByProduct($productId)
    {
        JobCategoryItem::where('product_id', $productId)->each(fn($item) => self::recalcItem($item));
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
