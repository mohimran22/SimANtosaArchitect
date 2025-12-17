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
use App\Http\Controllers\ProjectLevelController;
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
use App\Http\Controllers\DesignPackageController;


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



Route::middleware(['auth', 'permission:lihat daftar produk|lihat data produk'])->group(function () {
    Route::resource('/products', ProductController::class);
});

Route::middleware(['auth', 'permission:lihat daftar produk'])->group(function () {
    Route::resource('/products/catalog', ProductCatalogController::class);
});

Route::post('/products/generate-sku', [ProductController::class, 'generateSku'])
    ->name('products.generateSku');


Route::middleware(['auth', 'permission:lihat daftar gudang|lihat data gudang'])->group(function () {
    route::resource('/warehouses', WarehouseController::class);
});


Route::get('/warehouse/search-product', [SupplierCatalogController::class, 'searchProduct'])
    ->name('warehouse.searchProduct');

Route::post('/warehouse/products/store', [SupplierCatalogController::class, 'storeSupplierProduct'])
    ->name('warehouse.products.store');

Route::middleware(['auth', 'permission:lihat daftar proyek|lihat data proyek'])->group(function () {

    Route::get('/projects/{project}/continue', 
    [ProjectController::class, 'continue'])
    ->name('projects.continue');

    Route::resource('/projects', ProjectController::class);
    Route::get('prjects/{project}/pdf', [ProjectController::class, 'pdf'])
    ->name('projects.pdf');
});

Route::middleware(['auth', 'permission:lihat daftar proyek|lihat data proyek'])->group(function () {

    Route::resource('/labor_costs', \App\Http\Controllers\LaborCostController::class);
});

// CRUD paket desain
// Resource (tanpa show!)
Route::resource('design-packages', DesignPackageController::class)
    ->except(['show']);

// Tambah / update / hapus item
Route::post('design-packages/{designPackage}/items',
    [DesignPackageController::class, 'addItem'])->name('design-packages.items.store');

Route::put('design-package-items/{item}',
    [DesignPackageController::class, 'updateItem'])->name('design-packages.items.update');

Route::delete('design-package-items/{item}',
    [DesignPackageController::class, 'deleteItem'])->name('design-packages.items.delete');

// API aman untuk frontend
Route::get('design-packages/json/{id}',
    [DesignPackageController::class, 'getPackage'])->name('design-packages.json');




Route::post('projects/consultations', [\App\Http\Controllers\ConsultationController::class, 'store'])
    ->name('projects.consultations.store');

Route::put('consultations/{consultation}', [\App\Http\Controllers\ConsultationController::class, 'update'])
    ->name('consultations.update');

Route::get('consultations/{consultation}/pdf', [\App\Http\Controllers\ConsultationController::class, 'pdf'])
    ->name('consultations.pdf');

Route::post('projects/plannings', [\App\Http\Controllers\PlanningController::class, 'store'])
    ->name('projects.plannings.store');

Route::put('plannings/{planning}', [\App\Http\Controllers\PlanningController::class, 'update'])
    ->name('plannings.update');

Route::get('plannings/{planning}/pdf', [\App\Http\Controllers\PlanningController::class, 'pdf'])
    ->name('plannings.pdf');

Route::post('projects/surveys', [\App\Http\Controllers\SurveyController::class, 'store'])
    ->name('projects.surveys.store');

Route::put('surveys/{survey}', [\App\Http\Controllers\SurveyController::class, 'update'])
    ->name('surveys.update');

Route::get('surveys/{survey}/pdf', [\App\Http\Controllers\SurveyController::class, 'pdf'])
    ->name('surveys.pdf');

Route::post('projects/offers', [\App\Http\Controllers\OfferController::class, 'store'])
    ->name('projects.offers.store');
    

Route::put('offers/{offer}', [\App\Http\Controllers\OfferController::class, 'update'])
    ->name('offers.update');

Route::post('/offers/{offer}/approve', [\App\Http\Controllers\OfferController::class, 'approve'])
    ->name('offers.approve')
    ->middleware('auth');

Route::post('/offers/{offer}/reject', [\App\Http\Controllers\OfferController::class, 'reject'])
    ->name('offers.reject');


Route::get('/projects/offers/{offer}/pdf', [\App\Http\Controllers\OfferController::class, 'printPdf'])
    ->name('projects.offers.pdf');

// routes/web.php
Route::get(
    'projects/{project}/contract/pdf',
    [\App\Http\Controllers\ContractController::class, 'pdf']
)->name('projects.contract.pdf');

Route::get(
    'projects/{project}/invoice/pdf',
    [\App\Http\Controllers\InvoiceController::class, 'invoiceDp']
)->name('projects.invoice.pdf');

Route::post(
    '/projects/{project}/contract/approve',
    [\App\Http\Controllers\ContractController::class, 'approve']
)->name('projects.contract.approve');

Route::post(
    '/projects/{project}/invoice/approve',
    [\App\Http\Controllers\InvoiceController::class, 'approve']
)->name('projects.invoice.approve');

Route::post('/tasks/{task}/assign', [\App\Http\Controllers\ProjectTaskController::class, 'assign'])
    ->name('tasks.assign');

Route::post('/tasks/{task}/upload', [\App\Http\Controllers\ProjectTaskController::class, 'uploadFile'])
    ->name('tasks.upload');

Route::post('/tasks/{task}/approve', [\App\Http\Controllers\ProjectTaskController::class, 'approve'])
    ->name('tasks.approve');

Route::post('/tasks/{task}/reject', [\App\Http\Controllers\ProjectTaskController::class, 'reject'])
    ->name('tasks.reject');

Route::post('/tasks/{task}/complete', [\App\Http\Controllers\ProjectTaskController::class, 'complete'])
    ->name('tasks.complete');

Route::get('/tasks/files/{file}', [\App\Http\Controllers\ProjectTaskController::class, 'viewFile'])
    ->name('tasks.files.view');

Route::delete(
    '/tasks/files/{file}',
    [\App\Http\Controllers\ProjectTaskController::class, 'deleteFile']
)->name('tasks.files.delete');






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


