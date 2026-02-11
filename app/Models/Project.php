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
        'parent_project_id',
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

        public function invoicebuilds()
    {
        return $this->hasMany(InvoiceBuild::class);
    }

    public function tasks()
{
    return $this->hasMany(ProjectTask::class);
}

    public function rab()
{
    return $this->hasOne(RabProcess::class);
}

    public function finalDocument()
{
    return $this->hasOne(FinalProject::class);
}

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

    public function getCustomerNameAttribute()
{
    return $this->customer?->display_name ?? '-';
}

public function getEmployeeNameAttribute()
{
    return $this->employee?->display_name ?? '-';
}

public function latestSurveyInvoice()
{
    return $this->invoices()
        ->where('invoice_type', 'survey')
        ->latest()
        ->first();
}

public function generateLevels()
{
    $levels = match ((int) $this->project_type) {
        1 => [
                ['level_order' => 1, 'level_name' => 'Konsultasi'],
                ['level_order' => 2, 'level_name' => 'Rencana Survei'],
                ['level_order' => 3, 'level_name' => 'Survei'],
                ['level_order' => 4, 'level_name' => 'Penawaran Jasa Desain'],
                ['level_order' => 5, 'level_name' => 'Kontrak Desain'],
                ['level_order' => 6, 'level_name' => 'Invoice Desain DP'],
                ['level_order' => 7, 'level_name' => 'Proses Pengerjaan'],
                ['level_order' => 8, 'level_name' => 'Invoice Pelunasan Desain'],
                ['level_order' => 9, 'level_name' => 'Cetak & Softcopy'],
            ],
        2 => [
                ['level_order' => 1, 'level_name' => 'Konsultasi'],
                ['level_order' => 2, 'level_name' => 'Rencana Survei'],
                ['level_order' => 3, 'level_name' => 'Survei'],
                ['level_order' => 4, 'level_name' => 'Penawaran Pembuatan RAB'],
                ['level_order' => 5, 'level_name' => 'Invoice RAB'],
                ['level_order' => 6, 'level_name' => 'Proses Pengerjaan RAB'],
            ],
        3 => [
                ['level_order' => 1, 'level_name' => 'Konsultasi'],
                ['level_order' => 2, 'level_name' => 'Rencana Survei'],
                ['level_order' => 3, 'level_name' => 'Survei'],
                ['level_order' => 4, 'level_name' => 'Penawaran Jasa Build'],
                ['level_order' => 5, 'level_name' => 'Kontrak Kerja'],
                ['level_order' => 6, 'level_name' => 'Invoice Tahap 1'],
                ['level_order' => 7, 'level_name' => 'Pelaksanaan'],
                ['level_order' => 8, 'level_name' => 'Pelaksanaan & Pembayaran Tahap 2'],
                ['level_order' => 9, 'level_name' => 'Pelaksanaan & Pembayaran Tahap 3'],
                ['level_order' => 10, 'level_name' => 'Pelaksanaan & Pembayaran Tahap 4'],
            ],
        default => throw new \Exception('Jenis proyek tidak valid'),
    };

    $this->levels()->createMany($levels);
}

}
