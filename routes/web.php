<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountingAccountController;
use App\Http\Controllers\AccountingJournalController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\AffiliatorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\ArchitectController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductColorController;
use App\Http\Controllers\ProductBrandController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\SupplierCatalogController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\RoleSwitchController;
use App\Http\Controllers\UserImportController;
use App\Http\Controllers\MenuController;


Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'role:Super-Admin'])->group(function () {
    Route::resource('/menus', MenuController::class);
});

Route::get('/customer/profile', [DashboardController::class, 'edit'])->name('customer.profile');
Route::put('/customer/profile', [DashboardController::class, 'update'])->name('customer.update');
Route::get('/affiliators/profile', [DashboardController::class, 'edit'])->name('affiliators.profile');
Route::put('/affiliators/profile', [DashboardController::class, 'update'])->name('affiliators.update');



Route::middleware(['auth', 'permission:lihat daftar karyawan|lihat data karyawan'])->group(function () {
    Route::resource('/employees', EmployeeController::class)->whereUuid('employee');
});

Route::get('/employees/generate-nik', [EmployeeController::class, 'generateNikAjax'])
    ->name('employees.generateNik');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'permission:lihat daftar customer|lihat data customer', 'activerole:Customer'])
    ->group(function () {
        Route::get('/customers/generate-nic', [CustomersController::class, 'generateNicAjax'])->name('customers.generateNic');
        Route::resource('/customers', CustomersController::class)->whereUuid('customer');
    });

Route::middleware(['auth', 'permission:lihat daftar affiliator|lihat data affiliator', 'activerole:Affiliator'])
    ->group(function () {
        Route::get('/affiliators/generate-nia', [AffiliatorController::class, 'generateNiaAjax'])->name('affiliators.generateNia');
        Route::resource('/affiliators', AffiliatorController::class)->whereUuid('affiliator');
    });

Route::middleware(['auth', 'permission:lihat daftar supplier|lihat data supplier','activerole:Mitra Supplier' ])->group(function () {
    Route::get('/suppliers/generate-SupplierId', [SupplierController::class, 'generateSupplierIdAjax'])->name('suppliers.generateSupplierId');
    Route::resource('/suppliers', SupplierController::class);
});

// Supplier Catalog
Route::get('/supplier/search-product', [SupplierCatalogController::class, 'searchProduct'])
    ->name('supplier.searchProduct');

Route::get('/supplier/product-detail/{id}', [SupplierCatalogController::class, 'productDetail'])
    ->name('supplier.productDetail');

// Create product baru via AJAX (tidak pakai reload)
Route::post('/products/store-ajax', [ProductController::class, 'storeAjax'])
    ->name('products.store.ajax');

// Simpan produk ke supplier (pivot)
Route::post('/supplier/products/store', [SupplierCatalogController::class, 'storeSupplierProduct'])
    ->name('supplier.products.store');

Route::put('/supplier-product/update-price',
    [SupplierCatalogController::class, 'updatePrice']
)->name('supplier-product.update-price');

Route::get('/catalog/supplier', [ProductCatalogController::class, 'supplierCatalog'])
    ->name('catalog.supplier');

Route::get('/catalog/customer', [ProductCatalogController::class, 'customerCatalog'])
    ->name('catalog.customer');




 Route::middleware(['auth', 'permission:lihat daftar investor|lihat data investor', 'activerole:Investor'])->group(function () {
       Route::get('/investors/generate-InvestorId', [InvestorController::class, 'generateInvestorIdAjax'])->name('investors.generateInvestorId');
     Route::resource('/investors', InvestorController::class);
});

Route::middleware(['auth', 'permission:lihat daftar arsitek|lihat data arsitek', 'activerole:Mitra Arsitek'])->group(function () {
   Route::get('/architects/generate-ArchitectId', [ArchitectController::class, 'generateArchitectIdAjax'])->name('architects.generateArchitectId');
   Route::resource('/architects', ArchitectController::class);
});

Route::middleware(['auth', 'permission:lihat daftar tukang|lihat data tukang', 'activerole:Tukang'])->group(function () {
   Route::get('/workers/generate-WorkerId', [WorkerController::class, 'generateWorkerIdAjax'])->name('workers.generateWorkerId'); 
   Route::resource('/workers', WorkerController::class);
});

Route::middleware(['auth', 'permission:lihat daftar kontraktor|lihat data kontraktor', 'activerole:Mitra Kontraktor'])->group(function () {
    Route::get('/contractors/generate-SupplierId', [ContractorController::class, 'generateContractorIdAjax'])->name('contractors.generateContractorId');
    Route::resource('/contractors', ContractorController::class);
});

Route::middleware(['auth', 'role:Super-Admin|Akuntan'])
        ->resource('accounting', AccountingAccountController::class)
         ->parameters(['accounting' => 'account']);

Route::get('/journals/report', [AccountingJournalController::class, 'report'])
    ->name('journals.report')
    ->middleware(['role:Super-Admin|Akuntan']);

Route::middleware(['role:Super-Admin|Akuntan'])->group(function () {
    Route::resource('journals', AccountingJournalController::class);
});

Route::get('/periods/close', [AccountingClosingController::class, 'showCloseForm'])->name('periods.close.form');
Route::post('/periods/close', [AccountingClosingController::class, 'close'])->name('periods.close');

Route::post('/switch-role', [RoleSwitchController::class, 'switch'])
    ->middleware('auth')
    ->name('switch.role');

route::resource('/product_colors', ProductColorController::class);
route::resource('/product_brands', ProductBrandController::class);
route::resource('/product_categories', ProductCategoryController::class);
route::resource('/product_types', ProductTypeController::class);



Route::middleware(['auth', 'permission:lihat daftar produk'])->group(function () {
    Route::resource('/products', ProductController::class);
});

Route::middleware(['auth', 'permission:lihat daftar produk'])->group(function () {
    Route::resource('/products/catalog', ProductCatalogController::class);
});

Route::post('/products/generate-sku', [ProductController::class, 'generateSku'])
    ->name('products.generateSku');


Route::middleware(['auth', 'permission:lihat daftar gudang'])->group(function () {
    route::resource('/warehouses', WarehouseController::class);
});


Route::get('/warehouse/search-product', [SupplierCatalogController::class, 'searchProduct'])
    ->name('warehouse.searchProduct');

Route::post('/warehouse/products/store', [SupplierCatalogController::class, 'storeSupplierProduct'])
    ->name('warehouse.products.store');

Route::middleware(['auth', 'permission:lihat daftar proyek'])->group(function () {
    Route::resource('/projects', ProjectController::class);
});

Route::middleware(['auth', 'permission:lihat daftar user'])->group(function () {
    route::resource('/users', UsersController::class);
});

Route::middleware(['auth', 'permission:lihat daftar role'])->group(function () {
    Route::resource('/roles', RoleController::class);
    Route::post('/roles/{role}/update-permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');
});

Route::middleware(['auth'])->group(function () {
    // Route::resource('/accounts', AccountController::class);
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::post('/accounts/update-role', [AccountController::class, 'updateRole'])->name('accounts.update-role');
});

// Route::middleware(['auth', 'permission:lihat daftar dokumen'])->group(function () {
//     route::resource('/documents', DocumentController::class);
// });

// Route::middleware(['auth', 'permission:Lihat daftar rab'])->group(function () {
//     Route::get('/rab', [AccountController::class, 'index'])->name('accounts.index');
//     Route::post('/rab/update-role', [AccountController::class, 'updateRole'])->name('accounts.update-role');
// });



// Route::get('/license_holders/{id}/licenses', [LicenseHoldersController::class, 'showLicense'])->name('license_holders.licenses');
// Route::get('/license_holders/{id}/profile', [LicenseHoldersController::class, 'showProfile'])->name('license_holders.profile');
// Route::get('/license_holders/{id}/educations', [LicenseHoldersController::class, 'showTab'])->name('license_holders.educations');
// Route::get('/license_holders/{id}/workers', [LicenseHoldersController::class, 'showWorks'])->name('license_holders.workers');
// Route::get('/license_holders/{id}/families', [LicenseHoldersController::class, 'showFams'])->name('license_holders.families');





// Route::get('/employees/{id}/profile', [EmployeeController::class, 'showProfile'])->name('employees.profile');
// Route::get('/employees/{id}/educations', [EmployeeController::class, 'showTab'])->name('employees.educations');
// Route::get('/employees/{id}/workers', [EmployeeController::class, 'showWorks'])->name('employees.workers');
// Route::get('/employees/{id}/families', [EmployeeController::class, 'showFams'])->name('employees.families');

// route::resource('/employee_educations', EmployeeEducationController::class);
// route::resource('/employee_workers', EmployeeWorkExperienceController::class);
// route::resource('/employee_families', EmployeeFamilyMemberController::class);


Route::get('/api/cities/{province_id}', function ($province_id) {
    return \App\Models\City::where('province_id', $province_id)->select('id', 'name')->get();
});

Route::get('/api/districts/{city_id}', function ($city_id) {
    return \App\Models\District::where('city_id', $city_id)->select('id', 'name')->get();
});

Route::get('/api/sub_districts/{district_id}', function ($district_id) {
    return \App\Models\SubDistrict::where('district_id', $district_id)->select('id', 'name')->get();
});

Route::get('/api/postal_codes/{sub_district_id}', function ($sub_district_id) {
    return \App\Models\PostalCode::where('sub_district_id', $sub_district_id)->select('id', 'postal_code')->get();
});

Route::get('/api/banks', function () {
    return \App\Models\Bank::select('id', 'name', 'code')->orderBy('name')->get();
});




// Route::get('/import-licenses', [LicenseImportController::class, 'showForm'])->name('licenses.import.form');
// Route::post('/import-licenses', [LicenseImportController::class, 'import'])->name('licenses.import');

// Route::get('/import-users', [UserImportController::class, 'showForm'])->name('users.import.form');
// Route::post('/import-users', [UserImportController::class, 'import'])->name('users.import');


