<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = [
        'bidang',
        'kode_group',
        'nama_group',
        'kode',
        'kode_urut',
        'nama_pekerjaan',
        'satuan',
    ];
        public function group()
    {
        return $this->belongsTo(AhspGroup::class, 'ahsp_group_id');
    }
        public function items()
    {
        return $this->hasMany(JobCategoryItem::class);
    }
}
