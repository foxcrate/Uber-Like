<?php

namespace App\Http\Middleware;

use Closure;

class Lang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        if ($request->header('lang')) {
//            app()->setLocale($request->header('lang'));
//        }
//        return $next($request);
        $lang = ($request->hasHeader('lang')) ? $request->header('lang') : 'ar';
        //Set laravel localization
        app()->setLocale($lang);

        //Continue request
        return $next($request);
    }
}
