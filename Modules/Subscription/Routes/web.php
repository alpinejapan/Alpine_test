<?php

use Modules\Subscription\Http\Controllers\SubscriptionPlanController;
use Modules\Subscription\Http\Controllers\SubscriptionLogController;


Route::group(['as'=> 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'XSS','DEMO']],function (){
    Route::resource('subscription-plan', SubscriptionPlanController::class);
    Route::get('purchase-history', [SubscriptionLogController::class, 'index'])->name('purchase-history');
    Route::get('pending-purchase-history', [SubscriptionLogController::class, 'pending_index'])->name('pending-purchase-history');

    Route::get('purchase-history-detail/{id}', [SubscriptionLogController::class, 'show'])->name('purchase-history-detail');
    Route::delete('purchase-history-destroy/{id}', [SubscriptionLogController::class, 'destroy'])->name('purchase-history-destroy');
    Route::post('purchase-history-payment-approved/{id}', [SubscriptionLogController::class, 'approval_payment'])->name('purchase-history-payment-approved');
});


