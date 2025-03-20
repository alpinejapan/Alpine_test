<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\GeneralSetting\Entities\Setting;

class Timezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Setting::first();
        config(['app.timezone' => $setting->timezone]);
        date_default_timezone_set($setting->timezone);

        return $next($request);
    }
}
