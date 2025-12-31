<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategoryItem extends Model
{
    protected $fillable = [
        'job_category_id',
        'labor_cost_id',
        'equipment_cost_id',
        'product_id',
        'kode',
        'satuan',
        'koefisien',
        'base_unit_price',
        'total_price',
        'overhead',
        'profit',
        'subtotal',
        'grand_total',
    ];
        public function category()
    {
        return $this->belongsTo(JobCategory::class);
    }

                public function labors()
    {
        return $this->hasMany(LaborCost::class);
    }

            public function equipments()
    {
        return $this->hasMany(EquipmentCost::class);
    }

            public function products()
    {
        return $this->hasMany(Products::class);
    }
}
