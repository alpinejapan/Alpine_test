<?php

use Modules\Models\Http\Controllers\ModelsController;
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

    Route::resource('models', ModelsController::class);

});
Route::post('delete-models', [ModelsController::class,'deleteModels'])->name('delete-models');
