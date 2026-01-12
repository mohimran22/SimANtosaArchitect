<?php

namespace App\Observers;

use App\Models\EquipmentCost;
use App\Models\JobCategoryItem;

class EquipmentCostObserver
{
    /**
     * Handle the EquipmentCost "created" event.
     */
    public function created(EquipmentCost $equipmentCost): void
    {
        //
    }

    /**
     * Handle the EquipmentCost "updated" event.
     */
    public function updated(EquipmentCost $equipment): void
    {
            {
        $items = JobCategoryItem::where('equipment_cost_id', $equipment->id)->get();

        foreach ($items as $item) {
            app(JobCategoryItemService::class)->syncPrice($item);
        }
    }
    }

    /**
     * Handle the EquipmentCost "deleted" event.
     */
    public function deleted(EquipmentCost $equipmentCost): void
    {
        //
    }

    /**
     * Handle the EquipmentCost "restored" event.
     */
    public function restored(EquipmentCost $equipmentCost): void
    {
        //
    }

    /**
     * Handle the EquipmentCost "force deleted" event.
     */
    public function forceDeleted(EquipmentCost $equipmentCost): void
    {
        //
    }
}
