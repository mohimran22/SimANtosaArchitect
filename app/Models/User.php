<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUuid;

    protected $guard_name = 'web';
    

    public $incrementing = false;
    protected $keyType = 'string';

public function employee()
{
    return $this->hasOne(Employee::class);
}

    public function customer()
{
    return $this->hasOne(Customer::class);
}

    public function investor()
{
    return $this->hasOne(Investor::class);
}

    public function supplier()
{
    return $this->hasOne(Supplier::class);
}

    public function contractor()
{
    return $this->hasOne(Contractor::class);
}

    public function worker()
{
    return $this->hasOne(Worker::class);
}

    public function architect()
{
    return $this->hasOne(Architect::class);
}

    public function affiliator()
{
    return $this->hasOne(Affiliator::class);
}

public function activeRole()
{
    return $this->belongsTo(Role::class, 'active_role');
}

    
    protected $fillable = [
        'fullname',
        'nickname',
        'email',
        'password',
        'gender',
        'birth_place',
        'birth_date',
        'religion_id',
        'address',
        'province_id',
        'city_id',
        'district_id',
        'sub_district_id',
        'postal_code_id',
        'phone',
        'photo',
        'identity_number',
        'npwp',
        'bank_id',
        'account_number',
        'account_holder',
        'active_role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'identity_number',
        'remember_token',
        'account_number',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function religion() {
        return $this->belongsTo(Religion::class, 'religion_id');
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function subDistrict() {
        return $this->belongsTo(SubDistrict::class);
    }

    public function postalCode() {
        return $this->belongsTo(PostalCode::class);
    }

    public function bank()
{
    return $this->belongsTo(Bank::class);
}

    public function getPhotoUrlAttribute()
{
    if ($this->employee && $this->employee->photo) {
        return asset('storage/profile_photos/' . $this->employee->photo);
    }

    if ($this->licenseholder && $this->licenseholder->photo) {
        return asset('storage/profile_photos/' . $this->licenseholder->photo);
    }

    // fallback default
    return null;
}

public function getBirthDateFormattedAttribute()
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->format('d/m/Y') : '-';
    }

    public function getReadableGenderAttribute()
    {
    return [
        1 => 'Laki-Laki',
        2 => 'Perempuan',
    ][$this->gender] ?? 'Tidak diketahui';
    }

public function setActiveRoleAttribute($value)
{
    // Larangan nilai angka
    if (is_numeric($value)) {
        $this->attributes['active_role'] = null;
        return;
    }

    $this->attributes['active_role'] = $value;
}


}
