<?php

namespace Modules\GeneralSetting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GeneralSetting\Entities\Setting;
use Modules\GeneralSetting\Entities\CookieConsent;
use Modules\GeneralSetting\Entities\GoogleRecaptcha;
use Modules\GeneralSetting\Entities\TawkChat;
use Modules\GeneralSetting\Entities\GoogleAnalytic;
use Modules\GeneralSetting\Entities\FacebookPixel;
use Modules\GeneralSetting\Entities\SocialLoginInfo;
use Image, Str, File, Artisan;
use Modules\ContactMessage\Entities\ContactMessage;
use Modules\Newsletter\Entities\Subscriber;
use Modules\GeneralSetting\Entities\SeoSetting;

use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogTranslation;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogCategoryTranslation;
use Modules\Blog\Entities\BlogComment;
use Modules\Page\Entities\CustomPage;
use Modules\Page\Entities\CustomPageTranslation;
use Modules\Page\Entities\Faq;
use Modules\Page\Entities\FaqTranslate;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\AboutUs;
use Modules\Page\Entities\PrivacyPolicy;
use Modules\Page\Entities\TermAndCondition;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Entities\AboutUsTranslation;
use Modules\Language\Entities\Language;

use Modules\GeneralSetting\Http\Requests\GeneralSettingRequest;
use Modules\GeneralSetting\Http\Requests\CookieConsentRequest;
use Modules\GeneralSetting\Http\Requests\GoogleRecaptchaRequest;
use Modules\GeneralSetting\Http\Requests\TawkChatRequest;
use Modules\GeneralSetting\Http\Requests\GoogleAnalyticRequest;
use Modules\GeneralSetting\Http\Requests\FacebookPixelRequest;
use Modules\GeneralSetting\Http\Requests\SocialLoginInfoRequest;
use Modules\GeneralSetting\Http\Requests\HeaderFooterRequest;
use Modules\GeneralSetting\Http\Requests\SeoSettingRequest;

use Modules\Brand\Entities\Brand;
use Modules\Brand\Entities\BrandTranslation;
use Modules\Car\Entities\Car;
use Modules\Car\Entities\CarTranslation;
use Modules\Car\Entities\CarGallery;
use Modules\City\Entities\City;
use Modules\City\Entities\CityTranslation;
use Modules\Feature\Entities\Feature;
use Modules\Feature\Entities\FeatureTranslation;
use Modules\Subscription\Entities\SubscriptionPlan;
use Modules\Subscription\Entities\SubscriptionHistory;
use Modules\Testimonial\Entities\Testimonial;
use Modules\Testimonial\Entities\TestimonialTranslation;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\Page\Entities\ContactUsTranslation;

use App\Models\PaypalPayment;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\BankPayment;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;

use App\Models\Review;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\Admin;
use App\Models\JPYRate;



class GeneralSettingController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function general_setting()
    {
        $general_setting = Setting::first();
        $google_recaptcha_setting = GoogleRecaptcha::first();
        $tawk_chat_setting = TawkChat::first();
        $google_analytic_setting = GoogleAnalytic::first();
        $facebook_pixel_setting = FacebookPixel::first();

        return view('generalsetting::general_setting', compact('general_setting','google_recaptcha_setting','tawk_chat_setting','google_analytic_setting','facebook_pixel_setting'));
    }

    public function update_general_setting(GeneralSettingRequest $request){

        $general_setting = Setting::first();
        $general_setting->app_name = $request->app_name;
        $general_setting->selected_theme = $request->selected_theme;
        $general_setting->timezone = $request->timezone;
        $general_setting->contact_message_mail = $request->contact_message_mail;
        $general_setting->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function update_logo_favicon(Request $request){
        $rules = [  
            'logo'=>[
                'nullable',
                Rule::when(request('logo'),
                ['required','image','mimes:jpeg,png,jpg','max:2048'])
            ],
        ];

        $logo_setting = Setting::first();
        if($request->logo){
            $old_logo = $logo_setting->logo;
            $image = $request->logo;
            $ext = $image->getClientOriginalExtension();
            $logo_name = 'logo-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name = 'uploads/website-images/'.$logo_name;
            $logo = Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $logo_setting->logo = $logo_name;
            $logo_setting->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        if($request->logo_2){
            $old_logo = $logo_setting->logo_2;
            $image = $request->logo_2;
            $ext = $image->getClientOriginalExtension();
            $logo_name = 'logo2-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name = 'uploads/website-images/'.$logo_name;
            $logo = Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $logo_setting->logo_2 = $logo_name;
            $logo_setting->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        if($request->inner_logo){
            $old_logo = $logo_setting->inner_logo;
            $image = $request->inner_logo;
            $ext = $image->getClientOriginalExtension();
            $logo_name = 'inner_logo-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name = 'uploads/website-images/'.$logo_name;
            $logo = Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $logo_setting->inner_logo = $logo_name;
            $logo_setting->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }



        if($request->favicon){
            $old_favicon = $logo_setting->favicon;
            $favicon = $request->favicon;
            $ext = $favicon->getClientOriginalExtension();
            $favicon_name = 'favicon-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $favicon_name = 'uploads/website-images/'.$favicon_name;
            Image::make($request->favicon)
                    ->save(public_path().'/'.$favicon_name);
            $logo_setting->favicon = $favicon_name;
            $logo_setting->save();
            if($old_favicon){
                if(File::exists(public_path().'/'.$old_favicon))unlink(public_path().'/'.$old_favicon);
            }
        }

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function cookie_consent(){
        $cookie_consent_setting = CookieConsent::first();
        return view('generalsetting::cookie_consent', compact('cookie_consent_setting'));

    }

    public function update_cookie_consent(CookieConsentRequest $request){

        $cookie_consent_setting = CookieConsent::first();
        $cookie_consent_setting->status = $request->status ? 1 : 0;
        $cookie_consent_setting->message = $request->message;
        $cookie_consent_setting->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function update_google_captcha(GoogleRecaptchaRequest $request){

        $google_recaptcha_setting = GoogleRecaptcha::first();
        $google_recaptcha_setting->status = $request->status ? 1 : 0;
        $google_recaptcha_setting->site_key = $request->site_key;
        $google_recaptcha_setting->secret_key = $request->secret_key;
        $google_recaptcha_setting->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function update_tawk_chat(TawkChatRequest $request){

        $tawk_chat_setting = TawkChat::first();
        $tawk_chat_setting->status = $request->status ? 1 : 0;
        $tawk_chat_setting->chat_link = $request->chat_link;
        $tawk_chat_setting->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function update_google_analytic(GoogleAnalyticRequest $request){

        $google_analytic_setting = GoogleAnalytic::first();
        $google_analytic_setting->status = $request->status ? 1 : 0;
        $google_analytic_setting->analytic_id = $request->analytic_id;
        $google_analytic_setting->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function update_facebook_pixel(FacebookPixelRequest $request){

        $facebook_pixel_setting = FacebookPixel::first();
        $facebook_pixel_setting->app_id = $request->app_id;
        $facebook_pixel_setting->status = $request->status ? 1 : 0;
        $facebook_pixel_setting->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function error_image(){
        $error_setting = Setting::first();
        return view('generalsetting::error_image', compact('error_setting'));
    }

    public function update_error_image(Request $request){

        $error_setting = Setting::first();
        if($request->error_image){
            $old_logo = $error_setting->error_image;
            $image = $request->error_image;
            $ext = $image->getClientOriginalExtension();
            $logo_name = 'error-image-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name = 'uploads/website-images/'.$logo_name;
            $logo = Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $error_setting->error_image = $logo_name;
            $error_setting->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function login_image(){
        $login_page = Setting::first();
        return view('generalsetting::login_image', compact('login_page'));
    }

    public function update_login_image(Request $request){

        $login_page = Setting::first();
        if($request->login_page_bg){
            $old_logo = $login_page->login_page_bg;
            $image = $request->login_page_bg;
            $ext = $image->getClientOriginalExtension();
            $logo_name = 'login-image-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name = 'uploads/website-images/'.$logo_name;
            $logo = Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $login_page->login_page_bg = $logo_name;
            $login_page->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function admin_login_image(){
        $login_page = Setting::first();
        return view('generalsetting::admin_login_image', compact('login_page'));
    }

    public function admin_update_login_image(Request $request){

        $login_page = Setting::first();
        if($request->admin_login){
            $old_logo = $login_page->admin_login;
            $image = $request->admin_login;
            $ext = $image->getClientOriginalExtension();
            $logo_name = 'login-image-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name = 'uploads/website-images/'.$logo_name;
            $logo = Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $login_page->admin_login = $logo_name;
            $login_page->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function social_login(){
        $social_login = SocialLoginInfo::first();

        return view('generalsetting::social_login', ['social_login' => $social_login]);
    }

    public function update_social_login(SocialLoginInfoRequest $request){

        $social_login = SocialLoginInfo::first();

        $social_login->is_facebook = $request->is_facebook ? 1 : 0;
        $social_login->facebook_client_id = $request->facebook_client_id;
        $social_login->facebook_secret_id = $request->facebook_secret_id;
        $social_login->facebook_redirect_url = $request->facebook_redirect_url;
        $social_login->is_gmail = $request->is_gmail ? 1 : 0;
        $social_login->gmail_client_id = $request->gmail_client_id;
        $social_login->gmail_secret_id = $request->gmail_secret_id;
        $social_login->gmail_redirect_url = $request->gmail_redirect_url;
        $social_login->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function header_footer(){

        $header_footer = Setting::first();

        return view('generalsetting::header_footer', compact('header_footer'));
    }

    public function update_header_footer(HeaderFooterRequest $request){

        $header_footer = Setting::first();

        $header_footer->email = $request->email;
        $header_footer->phone = $request->phone;
        $header_footer->address = $request->address;
        $header_footer->about_us = $request->about_us;
        $header_footer->copyright = $request->copyright;
        $header_footer->twitter = $request->twitter;
        $header_footer->instagram = $request->instagram;
        $header_footer->linkedin = $request->linkedin;
        $header_footer->facebook = $request->facebook;
        $header_footer->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function default_avatar(){
        $default_avatar = Setting::first();

        return view('generalsetting::default_avatar', compact('default_avatar'));
    }

    public function update_default_avatar(Request $request){
        $default_avatar = Setting::first();

        if($request->default_avatar){
            $old_logo = $default_avatar->default_avatar;
            $image = $request->default_avatar;
            $ext = $image->getClientOriginalExtension();
            $logo_name = 'default-avatar-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name = 'uploads/website-images/'.$logo_name;
            $logo = Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $default_avatar->default_avatar = $logo_name;
            $default_avatar->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function breadcrumb(){
        $breadcrumb_setting = Setting::first();

        return view('generalsetting::breadcrumb', compact('breadcrumb_setting'));
    }

    public function update_breadcrumb(Request $request){
        $breadcrumb_setting = Setting::first();

        if($request->breadcrumb_image){
            $old_logo = $breadcrumb_setting->breadcrumb_image;
            $image = $request->breadcrumb_image;
            $ext = $image->getClientOriginalExtension();
            $logo_name = 'breadcrumb-image-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name = 'uploads/website-images/'.$logo_name;
            $logo = Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $breadcrumb_setting->breadcrumb_image = $logo_name;
            $breadcrumb_setting->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function seo_setting(){

        $seo_setting = SeoSetting::all();

        return view('generalsetting::seo_setting', compact('seo_setting'));
    }

    public function update_seo_setting(SeoSettingRequest $request, $id){

        $seo_item = SeoSetting::find($id);
        $seo_item->seo_title = $request->seo_title;
        $seo_item->seo_description = $request->seo_description;
        $seo_item->save();

        $notification = trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function cache_clear(){

        Artisan::call('optimize:clear');

        $notification = trans('translate.Cache cleared successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function maintenance_mode(){

        $maintenance_mode = Setting::first();

        return view('generalsetting::maintenance_mode', compact('maintenance_mode'));
    }

    public function update_maintenance_mode(Request $request){

        $maintenance_mode = Setting::first();
        $maintenance_mode->maintenance_text = $request->maintenance_text;
        $maintenance_mode->maintenance_status = $request->maintenance_status ? 1 : 0;
        $maintenance_mode->save();

        if($request->maintenance_image){
            $old_logo = $maintenance_mode->maintenance_image;
            $image = $request->maintenance_image;
            $ext = $image->getClientOriginalExtension();
            $logo_name = 'maintenance-image-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name = 'uploads/website-images/'.$logo_name;
            $logo = Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $maintenance_mode->maintenance_image = $logo_name;
            $maintenance_mode->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);


    }


    public function database_destroy(){

        Blog::truncate();
        BlogTranslation::truncate();
        BlogCategory::truncate();
        BlogCategoryTranslation::truncate();
        BlogComment::truncate();
        Brand::truncate();
        BrandTranslation::truncate();
        Car::truncate();
        CarTranslation::truncate();
        CarGallery::truncate();
        City::truncate();
        CityTranslation::truncate();
        ContactMessage::truncate();
        CustomPage::truncate();
        CustomPageTranslation::truncate();
        Faq::truncate();
        FaqTranslate::truncate();
        Feature::truncate();
        FeatureTranslation::truncate();
        Review::truncate();
        Subscriber::truncate();
        SubscriptionPlan::truncate();
        SubscriptionHistory::truncate();
        Testimonial::truncate();
        TestimonialTranslation::truncate();
        User::truncate();
        Wishlist::truncate();

        AboutUsTranslation::where('lang_code', '!=', 'en')->delete();
        ContactUsTranslation::where('lang_code', '!=', 'en')->delete();
        HomePageTranslation::where('lang_code', '!=', 'en')->delete();
        PrivacyPolicy::where('lang_code', '!=', 'en')->delete();
        TermAndCondition::where('lang_code', '!=', 'en')->delete();

        MultiCurrency::where('id', '!=', 1)->delete();
        Language::where('id', '!=', 1)->delete();


        $admins = Admin::where('id', '!=', 1)->get();
        foreach($admins as $admin){
            $admin_image = $admin->image;
            $admin->delete();
            if($admin_image){
                if(File::exists(public_path().'/'.$admin_image))unlink(public_path().'/'.$admin_image);
            }
        }


        $folderPath = public_path('uploads/custom-images');
        $response = File::deleteDirectory($folderPath);

        $path = public_path('uploads/custom-images');
        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        }

        PaypalPayment::where('id', 1)->update(['currency_id' => 1]);
        StripePayment::where('id', 1)->update(['currency_id' => 1]);
        RazorpayPayment::where('id', 1)->update(['currency_id' => 1]);
        Flutterwave::where('id', 1)->update(['currency_id' => 1]);
        PaystackAndMollie::where('id', 1)->update(['mollie_currency_id' => 1, 'paystack_currency_id' => 1]);
        InstamojoPayment::where('id', 1)->update(['currency_id' => 1]);

        $notification = trans('translate.Your database has been successfully cleared');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);


    }

    public function usd_setup(){
        $jpy_rate=JPYRate::first();
        return view('generalsetting::usd-currency',compact('jpy_rate'));
    }
    
    public function store_jpy_rate(Request $request){
        $jpy_rate=$request->jpy_rate;
        $rate = JPYRate::first();

    if ($rate) {
        // Update existing record
        $rate->yen_rate = $jpy_rate;
        $rate->save();
    } else {
        // Insert new record
        JPYRate::create(['yen_rate' => $jpy_rate]);
    }

    $notification = trans('translate.Your database has been successfully cleared');
    $notification = array('messege'=>$notification,'alert-type'=>'success');
    return redirect()->back()->with($notification);

    }
}
