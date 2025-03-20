<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Language\Entities\Language;
use Modules\Currency\app\Models\MultiCurrency;
use Session, Config;

class LangSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // for language

        if(!Session::get('front_lang')){
            $default_lang = Language::where('id', 1)->first();
            Session::put('front_lang', $default_lang->lang_code);
            Session::put('lang_dir', $default_lang->lang_direction);
            Session::put('front_lang_name', $default_lang->lang_name);

            app()->setLocale($default_lang->lang_code);
        }else{
            $is_exist = Language::where('lang_code', Session::get('front_lang'))->first();
            if(!$is_exist){
                Session::put('front_lang', 'en');
                Session::put('lang_dir', 'left_to_right');
                Session::put('front_lang_name', 'English');
            }

            app()->setLocale(Session::get('front_lang'));
        }

        // for currency
        if(!Session::get('currency_code')){

            $default_currency = MultiCurrency::where('id', 1)->first();

            Session::put('currency_name', $default_currency->currency_name);
            Session::put('currency_code', $default_currency->currency_code);
            Session::put('currency_icon', $default_currency->currency_icon);
            Session::put('currency_rate', $default_currency->currency_rate);
            Session::put('currency_position', $default_currency->currency_position);

        }else{
            $session_currency = MultiCurrency::where('currency_code', Session::get('currency_code'))->first();
            if(!$session_currency){
                $default_currency = MultiCurrency::where('id', 1)->first();

                Session::put('currency_name', $default_currency->currency_name);
                Session::put('currency_code', $default_currency->currency_code);
                Session::put('currency_icon', $default_currency->currency_icon);
                Session::put('currency_rate', $default_currency->currency_rate);
                Session::put('currency_position', $default_currency->currency_position);
            }
        }
        return $next($request);
    }
}
