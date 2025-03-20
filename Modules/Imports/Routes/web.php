<?php

use Illuminate\Support\Facades\Route;
use Modules\Imports\Http\Controllers\ImportsController;


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

Route::group(['as'=> 'admin.', 'prefix' => 'admin', 'middleware' => ['XSS','DEMO','auth:admin']],function (){
    Route::resource('import', ImportsController::class);
    Route::get('companies',[ImportsController::class,'companies'])->name('companies');
    Route::post('store-company',[ImportsController::class,'storeCompany'])->name('store-company');
    Route::get('imported-list',[ImportsController::class,'commission'])->name('commission');
    Route::post('store-car-comission',[ImportsController::class,'commissionStore'])->name('store-car-comission');
    Route::post('store-car-shipping',[ImportsController::class,'shippingStore'])->name('store-car-shipping');
    Route::post('store-all-comission',[ImportsController::class,'storeAllComission'])->name('store-all-comission');
    Route::post('store-all-shipping',[ImportsController::class,'storeAllShipping'])->name('store-all-shipping');
    Route::post('update-values',[ImportsController::class,'updateValuesWithValidation'])->name('update-values');
    Route::post('store-car-insurance',[ImportsController::class,'storeCarInsurance'])->name('store-car-insurance');
    Route::post('store-car-inland',[ImportsController::class,'storeCarInland'])->name('store-car-inland');
    Route::post('import/bulk-delete',[ImportsController::class,'bulkDelete'])->name('import.bulk-delete');
    Route::post('change_top_sell',[ImportsController::class,'TopSale'])->name('change_top_sell');
    Route::post('change_new_arrivals',[ImportsController::class,'NewArrival'])->name('change_new_arrivals');
});
