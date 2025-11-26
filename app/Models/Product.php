<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class Product extends Model
{
    public function color() {

    return $this->belongsTo(ProductColor::class, 'color_id');
}

    public function brand() {

    return $this->belongsTo(ProductBrand::class, 'brand_id');
}

    public function category() {

    return $this->belongsTo(ProductCategory::class, 'category_id');
}

    public function type() {

    return $this->belongsTo(ProductType::class, 'type_id');
}

public function suppliers()
{
    return $this->belongsToMany(Supplier::class, 'product_supplier')
                ->using(ProductSupplier::class)
                ->withPivot(['buying_prices', 'selling_prices', 'special_prices', 'stock'])
                ->withTimestamps();
}


public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}

// public function owners()
// {
//     return $this->belongsToMany(User::class, 'license_user', 'license_id', 'user_id');
// }

// public function employees()
// {
//     return $this->belongsToMany(Employee::class, 'employee_license', 'license_id', 'employee_id');
// }

// public function students()
// {
//     return $this->hasMany(Student::class);
// }

//   public function accounts()
//     {
//         return $this->hasMany(AccountingAccount::class);
//     }

//  public function journals()
//     {
//         return $this->hasMany(AccountingJournal::class);
//     }



    // Di model License

    use HasFactory, HasUuid;

    protected $table = 'products';
    protected $keyType = 'string';
    public $incrementing = false;

      protected $fillable = [
        'name', 'photo', 'description',
        'unit_1_name', 'unit_1_value',
        'unit_2_name', 'unit_2_value',
        'unit_3_name', 'unit_3_value',
        'unit_4_name', 'unit_4_value',
        'brand_id', 'category_id', 'type_id',
        'color_id', 'volume', 'size',
        'buying_prices', 'selling_prices', 'special_prices',
        'tax_percentage', 
        'inventory_account_id', 'sales_account_id', 'hpp_account_id',
        'status'
    ];

}
