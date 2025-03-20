<?php

use Modules\Cars\Http\Controllers\CarsController;


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
    Route::resource('cars', CarsController::class);
    Route::post('store-car-comission', [CarsController::class,'storeCarComission'])->name('store-car-comission');
    Route::post('store-car-shipping-id', [CarsController::class,'storeShippingIdWise'])->name('store-car-shipping-id');
    Route::post('store-all-car-comission', [CarsController::class,'storeAllCarComission'])->name('store-all-car-comission');
    Route::post('store-all-jdm-car-insurance', [CarsController::class,'storeAllCarInsurance'])->name('store-all-jdm-car-insurance');
    Route::post('store-jdm-car-insurance-by-id', [CarsController::class,'storeCarInsuranceById'])->name('store-jdm-car-insurance-by-id');
    Route::post('store-all-car-shipping', [CarsController::class,'storeAllCarShipping'])->name('store-all-car-shipping');
    Route::post('blog-new-arrival-cars', [CarsController::class,'newArrivalCars'])->name('blog-new-arrival-cars');
});
Route::post('delete-car', [CarsController::class,'deleteCar'])->name('delete-car');
