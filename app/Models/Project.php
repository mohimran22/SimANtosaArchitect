<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasUuids;

    protected $table = 'projects';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false; // kalau tabel tidak punya created_at / updated_at

    protected $fillable = [
        'project_code',
        'project_name',
        'project_type',
        'project_location',
        'province_id',
        'city_id',
        'district_id',
        'sub_district_id',
        'postal_code_id',
        'customer_id',
        'employee_id',
        'affiliator_id',
        'end_date',
        'start_date',
        'project_status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function subDistrict()
    {
        return $this->belongsTo(SubDistrict::class);
    }

    public function postalCode()
    {
        return $this->belongsTo(PostalCode::class, 'postal_code_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function affiliator()
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id');
    }

    public function levels()
    {
        return $this->hasMany(ProjectLevel::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

        public function planning()
    {
        return $this->hasOne(Planning::class);
    }

            public function survey()
    {
        return $this->hasOne(Survey::class);
    }

    public function offer()
    {
        return $this->hasOne(Offer::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function tasks()
{
    return $this->hasMany(ProjectTask::class);
}



    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    public function getCurrentLevelAttribute()
    {
        return $this->levels()
            ->where('is_completed', false)
            ->orderBy('level_order')
            ->first();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {

            if (!$project->id) {
                $project->id = (string) Str::uuid();
            }

            if (!$project->project_code) {
                $project->project_code = 'PRJ-' . strtoupper(Str::random(6));
            }
        });
    }
}
