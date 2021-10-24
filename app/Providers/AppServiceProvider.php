<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
         app()->singleton('lang',function(){
             if(auth()->user()){
                 if(empty(auth()->user()->lang)){
                     return 'ar';
                 }else{
                     return auth()->user()->lang;
                 }
             }
             else{
                 if(session()->has('lang'))
                 {
                     return session()->get('lang');
                 }
                 else{
                     return 'ar';
                 }
             }
         });


         Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
