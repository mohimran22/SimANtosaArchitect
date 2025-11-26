<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LicenseHolder extends Model
{
    public function getMarriedDateFormattedAttribute()
    {
        return $this->married_date ? Carbon::parse($this->married_date)->format('d/m/Y') : '-';
    }

    public function getBirthDateFormattedAttribute()
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->format('d/m/Y') : '-';
    }

    public function getReadableMaritalStatusAttribute()
    {
    return [
        1 => 'Lajang',
        2 => 'Menikah',
        3 => 'Duda',
        4 => 'Janda',
    ][$this->marital_status] ?? 'Tidak diketahui';
    }

   

    // app/Models/LicenseHolder.php

public function getReadableLanguagesAttribute()
{
    $level = [
        1 => 'Lancar',
        2 => 'Tidak Lancar',
    ];

    return [
        'Indonesia (Baca/Tulis)' => $level[$this->indonesian_literacy] ?? 'Tidak diketahui',
        'Indonesia (Bicara)' => $level[$this->indonesian_proficiency] ?? 'Tidak diketahui',
        'Arab (Baca/Tulis)' => $level[$this->arabic_literacy] ?? 'Tidak diketahui',
        'Arab (Bicara)' => $level[$this->arabic_proficiency] ?? 'Tidak diketahui',
        'Inggris (Baca/Tulis)' => $level[$this->english_literacy] ?? 'Tidak diketahui',
        'Inggris (Bicara)' => $level[$this->english_proficiency] ?? 'Tidak diketahui',
    ];
}

    public function user()
{
    return $this->belongsTo(User::class);
}

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

    public function religion()
{
    return $this->belongsTo(Religion::class, 'religion_id');
}

    public function educations()
{
    return $this->hasMany(LicenseHolderEducation::class);
}

     public function workers()
{
    return $this->hasMany(LicenseHolderWorkExperiences::class);
}

    public function families()
{
    return $this->hasMany(LicenseHolderFamilyMembers::class);
}

    use HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'fullname',
        'nickname',
        'gender',
        'religion_id',
        'identity_number',
        'driver_license_number',
        'birth_place',
        'birth_date',
        'address',
        'province_id',
        'city_id',
        'district_id',
        'sub_district_id',
        'postal_code_id',
        'phone',
        'hobby',
        'marital_status',
        'married_date',
        'indonesian_literacy',
        'indonesian_proficiency',
        'arabic_literacy',
        'arabic_proficiency',
        'english_literacy',
        'english_proficiency',
        'photo',
        
    ];

}
