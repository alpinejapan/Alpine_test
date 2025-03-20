<?php

use Illuminate\Support\Facades\Route;

/*  Admin panel Controller  */

use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdsBannerController;

use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;

/* Admin panel Controller  */

// start user panel
use App\Http\Controllers\Auth\PasswordResetLinkController as UserPasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController as UserNewPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController as UserRegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController as UserAuthenticatedSessionController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaypalController;
// end user panel

use Modules\GeneralSetting\Entities\Setting;

Route::group(['middleware' => ['XSS','DEMO']], function () {

    Route::group(['middleware' => ['HtmlSpecialchars', 'MaintenanceChecker']], function () {

        Route::controller(HomeController::class)->group(function () {

            Route::get('/', 'index')->name('home');
            Route::get('/about-us', 'about_us')->name('about-us');
            Route::get('/contact-us', 'contact_us')->name('contact-us');
            Route::get('/terms-conditions', 'terms_conditions')->name('terms-conditions');
            Route::get('/privacy-policy', 'privacy_policy')->name('privacy-policy');
            Route::get('/faq', 'faq')->name('faq');

            Route::get('/compare', 'compare')->name('compare');
            Route::get('/add-to-compare/{id}', 'add_to_compare')->name('add-to-compare');
            Route::delete('/remove-to-compare/{id}', 'remove_to_compare')->name('remove-to-compare');

            Route::get('/blogs', 'blogs')->name('blogs');
            Route::get('/blog/{slug}', 'blog_show')->name('blog');
            Route::post('/store-comment', 'store_comment')->name('store-comment');

            Route::get('/page/{slug}', 'custom_page')->name('custom-page');

            Route::get('/listings', 'listings')->name('listings');
            Route::get('/listing/{slug}', 'listing')->name('listing');

            Route::get('/dealers', 'dealers')->name('dealers');
            Route::get('/dealer/{slug}', 'dealer')->name('dealer');
            Route::post('/send-message-to-dealer/{id}', 'send_message_to_dealer')->name('send-message-to-dealer');

            Route::get('/join-as-dealer', 'join_as_dealer')->name('join-as-dealer');

            Route::get('/pricing-plan', 'pricing_plan')->name('pricing-plan');

            Route::get('/language-switcher', 'language_switcher')->name('language-switcher');
            Route::get('/currency-switcher', 'currency_switcher')->name('currency-switcher');

        });

        Route::get('pricing-plan-enroll/{id}', [PaymentController::class, 'payment'])->name('pricing-plan-enroll');

        Route::controller(PaymentController::class)->group(function () {

            Route::get('/payment/{slug}', 'payment')->name('payment');

            Route::post('/pay-via-stripe/{id}', 'pay_via_stripe')->name('pay-via-stripe');
            Route::post('/pay-via-bank/{slug}', 'pay_via_bank')->name('pay-via-bank');
            Route::post('/pay-via-razorpay/{slug}', 'pay_via_razorpay')->name('pay-via-razorpay');
            Route::post('/pay-via-flutterwave/{slug}', 'pay_via_flutterwave')->name('pay-via-flutterwave');
            Route::get('/pay-via-paystack/{slug}', 'pay_via_payStack')->name('pay-via-paystack');
            Route::get('/pay-via-mollie/{slug}', 'pay_via_mollie')->name('pay-via-mollie');
            Route::get('/mollie-payment-success', 'mollie_payment_success')->name('mollie-payment-success');
            Route::get('/pay-via-instamojo/{slug}', 'pay_via_instamojo')->name('pay-via-instamojo');
            Route::get('/response-instamojo', 'instamojo_response')->name('response-instamojo');

        });

        Route::get('/pay-via-paypal/{id}',[PaypalController::class, 'pay_via_paypal'])->name('pay-via-paypal');
        Route::get('/paypal-success-payment',[PaypalController::class, 'paypal_success_payment'])->name('paypal-success-payment');
        Route::get('/paypal-faild-payment',[PaypalController::class, 'paypal_faild_payment'])->name('paypal-faild-payment');

        Route::group(['as'=> 'user.', 'prefix' => 'user', 'middleware' => ['auth:web']],function (){

            Route::controller(ProfileController::class)->group(function () {

                Route::get('/dashboard', 'dashboard')->name('dashboard');

                Route::get('/edit-profile', 'edit')->name('edit-profile');
                Route::put('/update-profile', 'update')->name('update-profile');

                Route::get('/change-password', 'change_password')->name('change-password');
                Route::post('/update-password', 'update_password')->name('update-password');
                Route::post('/upload-user-avatar', 'upload_user_avatar')->name('upload-user-avatar');

                Route::get('/pricing-plan', 'pricing_plan')->name('pricing-plan');

                Route::get('/orders', 'orders')->name('orders');

                Route::get('/wishlists', 'wishlists')->name('wishlists');
                Route::get('/add-to-wishlist/{id}', 'add_to_wishlist')->name('add-to-wishlist');
                Route::delete('/remove-wishlist/{id}', 'remove_wishlist')->name('remove-wishlist');

                Route::get('/reviews', 'reviews')->name('reviews');
                Route::post('/store-review', 'store_review')->name('store-review');
            });

        });

        Route::post('/forget-password', [UserPasswordResetLinkController::class, 'custom_forget_password'])->name('forget-password');
        Route::get('/reset-password-page', [UserNewPasswordController::class, 'custom_reset_password_page'])->name('reset-password-page');
        Route::post('/reset-password-store/{token}', [UserNewPasswordController::class, 'custom_reset_password_store'])->name('reset-password-store');

        Route::get('/user-verification', [UserRegisteredUserController::class, 'custom_user_verification'])->name('user-verification');

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        Route::controller(UserAuthenticatedSessionController::class)->group(function () {
            Route::get('login/google', 'redirect_to_google')->name('login-google');
            Route::get('/callback/google', 'google_callback')->name('callback-google');

            Route::get('login/facebook', 'redirect_to_facebook')->name('login-facebook');
            Route::get('/callback/facebook', 'facebook_callback')->name('callback-facebook');
        });

    });

    require __DIR__.'/auth.php';

    Route::group(['as'=> 'admin.', 'prefix' => 'admin'],function (){

        /* Start admin auth route */
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

        Route::post('store-login', [AuthenticatedSessionController::class, 'store'])->name('store-login');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->name('logout');

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

        /* End admin auth route */

        Route::group(['middleware' => ['auth:admin']], function () {
            Route::get('/', [DashboardController::class, 'dashboard']);
            Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

            Route::controller(AdminProfileController::class)->group(function () {
                Route::get('edit-profile', 'edit_profile')->name('edit-profile');
                Route::put('profile-update', 'profile_update')->name('profile-update');
                Route::put('update-password', 'update_password')->name('update-password');
            });

            Route::controller(UserController::class)->group(function () {
                Route::get('user-list', 'user_list')->name('user-list');
                Route::get('pending-user', 'pending_user')->name('pending-user');
                Route::get('user-show/{id}', 'user_show')->name('user-show');
                Route::delete('user-delete/{id}', 'user_destroy')->name('user-delete');
                Route::put('user-status/{id}', 'user_status')->name('user-status');
                Route::put('user-update/{id}', 'update')->name('user-update');
            });

            Route::controller(AdsBannerController::class)->group(function () {
                Route::get('ads-banner', 'index')->name('ads-banner');
                Route::put('ads-banner-update/{id}', 'update')->name('ads-banner-update');
            });
        });



    });

});


Route::get('/migrate-for-update', function(){

    Artisan::call('migrate');

    $general_setting = Setting::first();
    $general_setting->app_version = '1.1';
    $general_setting->save();

    Artisan::call('optimize:clear');

    $notification = "Version updated successfully";
    $notification = array('messege' => $notification, 'alert-type' => 'success');
    return redirect()->route('home')->with($notification);
});

