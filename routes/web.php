<?php

use App\Http\Controllers\AdvertiseCollectionController;
use App\Http\Controllers\AdvertiseController;
use App\Http\Controllers\AliasCollectionController;
use App\Http\Controllers\CalculateController;
use App\Http\Controllers\CalculateRevenueAdvertiseController;
use App\Http\Controllers\CampaignCollectionController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);

    return redirect('login');
});


Route::resource('users', UserController::class)->middleware(['auth', 'verified', 'role:admin']);

Route::resource('roles', RoleController::class)->middleware(['auth', 'verified', 'role:admin']);

// Route::resource('websites', WebsiteController::class)->middleware(['auth', 'verified']);
Route::controller(WebsiteController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('websites/import', 'import')->middleware(['permission:website-list'])->name('websites.import');
    Route::post('websites/import/store','storeImport')->middleware(['permission:website-create'])->name('websites.store_import');
    Route::get('websites', 'index')->middleware(['permission:website-list'])->name('websites.index');
    Route::post('websites', 'store')->middleware(['permission:website-create'])->name('websites.store');
    Route::get('websites/create', 'create')->middleware(['permission:website-create'])->name('websites.create');
    Route::patch('websites/{website}', 'update')->middleware(['permission:website-edit'])->name('websites.update');
    Route::delete('websites/{website}', 'destroy')->middleware(['permission:website-delete'])->name('websites.destroy');
    Route::get('websites/{website}/edit', 'edit')->middleware(['permission:website-edit'])->name('websites.edit');
});

// Route::resource('permissions', PermissionController::class)->middleware(['auth', 'verified', 'role:admin'])->only(['store']);
Route::controller(PermissionController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('permissions', 'index')->middleware(['permission:permission-list'])->name('permissions.index');
    Route::post('permissions', 'store')->middleware(['permission:permission-create'])->name('permissions.store');
    Route::get('permissions/create', 'create')->middleware(['permission:permission-create'])->name('permissions.create');
    Route::patch('permissions/{permission}', 'update')->middleware(['permission:permission-edit'])->name('permissions.update');
    Route::delete('permissions/{permission}', 'destroy')->middleware(['permission:permission-delete'])->name('permissions.destroy');
    Route::get('permissions/{permission}/edit', 'edit')->middleware(['permission:permission-edit'])->name('permissions.edit');
});


// Route::resource('advertisements', AdvertiseController::class)->middleware(['auth', 'verified']);
Route::controller(AdvertiseController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('advertisements', 'index')->middleware(['permission:advertisement-list'])->name('advertisements.index');
    Route::post('advertisements', 'store')->middleware(['permission:advertisement-create'])->name('advertisements.store');
    Route::get('advertisements/create', 'create')->middleware(['permission:advertisement-create'])->name('advertisements.create');
    Route::patch('advertisements/{advertisement}', 'update')->middleware(['permission:advertisement-edit'])->name('advertisements.update');
    Route::delete('advertisements/{advertisement}', 'destroy')->middleware(['permission:advertisement-delete'])->name('advertisements.destroy');
    Route::get('advertisements/{advertisement}/edit', 'edit')->middleware(['permission:advertisement-edit'])->name('advertisements.edit');
});

// Route::get('advertise-collection/import', [AdvertiseCollectionController::class, 'import'])->middleware(['auth', 'verified'])->name('advertise-collection.import');
// Route::post('advertise-collection/import/store', [AdvertiseCollectionController::class, 'storeImport'])->middleware(['auth', 'verified'])->name('advertise-collection.store_import');
// Route::resource('advertise-collection', AdvertiseCollectionController::class)->middleware(['auth', 'verified']);
Route::controller(AdvertiseCollectionController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('advertise-collection/import', 'import')->middleware(['permission:advertise-collection-list'])->name('advertise-collection.import');
    Route::post('advertise-collection/import/store','storeImport')->middleware(['permission:advertise-collection-create'])->name('advertise-collection.store_import');
    Route::get('advertise-collection', 'index')->middleware(['permission:advertise-collection-list'])->name('advertise-collection.index');
    Route::post('advertise-collection', 'store')->middleware(['permission:advertise-collection-create'])->name('advertise-collection.store');
    Route::get('advertise-collection/create', 'create')->middleware(['permission:advertise-collection-create'])->name('advertise-collection.create');
    Route::patch('advertise-collection/{advertise_collection}', 'update')->middleware(['permission:advertise-collection-edit'])->name('advertise-collection.update');
    Route::delete('advertise-collection/{advertise_collection}', 'destroy')->middleware(['permission:advertise-collection-delete'])->name('advertise-collection.destroy');
    Route::get('advertise-collection/{advertise_collection}/edit', 'edit')->middleware(['permission:advertise-collection-edit'])->name('advertise-collection.edit');
});

// Route::resource('revenue-advertise', CalculateRevenueAdvertiseController::class)->middleware(['auth', 'verified'])->only(['index']);

// Route::get('revenue-advertise/record', [CalculateRevenueAdvertiseController::class, 'record'])->middleware(['auth', 'verified'])->name('revenue-advertise.record');
Route::controller(CalculateRevenueAdvertiseController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('revenue-advertise', 'index')->middleware(['permission:revenue-advertise-list'])->name('revenue-advertise.index');
    Route::get('revenue-advertise/record','record')->middleware(['permission:revenue-advertise-create'])->name('revenue-advertise.record');
});

Route::controller(AliasCollectionController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('alias-collections', 'index')->middleware(['permission:alias-collection-list'])->name('alias-collection.index');
    Route::post('alias-collections', 'store')->middleware(['permission:alias-collection-create'])->name('alias-collection.store');
    Route::get('alias-collections/create', 'create')->middleware(['permission:alias-collection-create'])->name('alias-collection.create');
    Route::patch('alias-collections/{alias_collection}', 'update')->middleware(['permission:alias-collection-edit'])->name('alias-collection.update');
    Route::delete('alias-collections/{alias_collection}', 'destroy')->middleware(['permission:alias-collection-delete'])->name('alias-collection.destroy');
    Route::get('alias-collections/{alias_collection}/edit', 'edit')->middleware(['permission:alias-collection-edit'])->name('alias-collection.edit');
});

Route::controller(CampaignController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('campaigns', 'index')->middleware(['permission:campaign-list'])->name('campaigns.index');
    Route::post('campaigns', 'store')->middleware(['permission:campaign-create'])->name('campaigns.store');
    Route::get('campaigns/create', 'create')->middleware(['permission:campaign-create'])->name('campaigns.create');
    Route::patch('campaigns/{campaign}', 'update')->middleware(['permission:campaign-edit'])->name('campaigns.update');
    Route::delete('campaigns/{campaign}', 'destroy')->middleware(['permission:campaign-delete'])->name('campaigns.destroy');
    Route::get('campaigns/{campaign}/edit', 'edit')->middleware(['permission:campaign-edit'])->name('campaigns.edit');
});

Route::controller(CampaignCollectionController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('campaign-collection', 'index')->middleware(['permission:campaign-collection-list'])->name('campaign-collection.index');
    Route::post('campaign-collection', 'store')->middleware(['permission:campaign-collection-create'])->name('campaign-collection.store');
    Route::get('campaign-collection/create', 'create')->middleware(['permission:campaign-collection-create'])->name('campaign-collection.create');
    Route::patch('campaign-collection/{campaign_collection}', 'update')->middleware(['permission:campaign-collection-edit'])->name('campaign-collection.update');
    Route::delete('campaign-collection/{campaign_collection}', 'destroy')->middleware(['permission:campaign-collection-delete'])->name('campaign-collection.destroy');
    Route::get('campaign-collection/{campaign_collection}/edit', 'edit')->middleware(['permission:campaign-collection-edit'])->name('campaign-collection.edit');
});

Route::resource('calculated', CalculateController::class)->middleware(['auth', 'verified']);

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard',[DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.index');
Route::get('/campaign-collection/dashboard',[CampaignController::class,'dashboard'])->middleware(['auth','verified'])->name('campaign-collection.dashboard');
Route::get('/campaign-collection/detail/{id}',[CampaignController::class,'detail'])->middleware(['auth','verified'])->name('campaign-collection.detail');
require __DIR__ . '/auth.php';
