<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    public function licenseHolders()
{
    return $this->hasMany(LicenseHolder::class, 'religion_id');
}

public function employees()
{
    return $this->hasMany(Employee::class, 'religion_id');
}

public function students()
{
    return $this->hasMany(Student::class, 'student_id');
}

}
