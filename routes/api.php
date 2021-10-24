<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/test',"UserApiControllerOnly@socket");
Route::post('/test2',"UserApiControllerOnly@test2");

Route::get('/testServer',"FawzyController@testServer");

Route::get('/get_andriod_version',"UserApiControllerOnly@get_andriod_version");

Route::get('/cars', 'Resource\ItineraryController@cars')->name('itinerary.cars');

//Route::post('/user/rate_user', 'UserApiControllerOnly@rateUser');

Route::group(['middleware' => ['language']], function () {
    Route::post('/user/signup', 'UserApiControllerOnly@signup');
    Route::post('/user/logout', 'UserApiControllerOnly@logout');
    Route::post('/auth/facebook', 'Auth\SocialLoginController@facebookViaAPI');
    Route::post('/auth/google', 'Auth\SocialLoginController@googleViaAPI');
    Route::post('/oauth/token', 'UserApiControllerOnly@authenticate');
    Route::post('/login_user', 'UserApiControllerOnly@loginUser');
    Route::post('/user/refresh', 'UserApiControllerOnly@refresh');
    Route::get('/user/account_ext', 'UserApiControllerOnly@checkAccount');
    Route::post('/user/check_email_phone', 'UserApiControllerOnly@checkEmailAndMobile');
    // Route::get('/user/check_stuts', 'UserApiControllerOnly@checkEmailAndMobile');
    Route::post('/user/forgot/password', 'UserApiControllerOnly@forgot_password');
//    Route::post('/user/forgot/password', 'UserApiControllerOnly@forgetPasswordUser')->name('forget-password-user');
    Route::post('/user/reset/password', 'UserApiControllerOnly@reset_password')->name('reset-password-user');
    Route::get('/user/app_details', 'UserApiControllerOnly@App_Details');
    Route::get('show-order', 'UserApiControllerOnly@showOrder');



    Route::group(['middleware' => ['auth:api']], function () {
        // user profile
        Route::get('/user/estimated/fare', 'UserApiControllerOnly@estimated_fare');
        Route::get('/user/GetDrivingDistance', 'UserApiControllerOnly@GetDrivingDistance');
        Route::post('/user/app_destroy', 'UserApiControllerOnly@Destroy_App');
        Route::get('/user/searching_user', 'UserApiControllerOnly@searchingUser');

        Route::get('/user/governments', 'UserApiControllerOnly@governments');


        //Route::get('/user/details', 'UserApiControllerOnly@details');
        Route::post('/user/details', 'UserApiControllerOnly@details');
        // Route::get('/user/check_stuts_request', 'UserApiControllerOnly@check_stuts_request');
        Route::post('/user/change/password', 'UserApiControllerOnly@change_password');

        Route::post('/user/update/location', 'UserApiControllerOnly@update_location');


        Route::post('/user/update/profile', 'UserApiControllerOnly@update_profile');

        // services

        Route::get('/user/services', 'UserApiControllerOnly@services');

        // provider

        Route::post('/user/rate/provider', 'UserApiControllerOnly@rate_provider');

        // request

        Route::post('/user/send/request', 'UserApiControllerOnly@send_request');

        Route::post('/user/cancel/request', 'UserApiControllerOnly@cancel_request');
        Route::post('/user/schedule_searching', 'UserApiControllerOnly@scheduleSearching');
        Route::post('/user/edit/trip', 'UserApiControllerOnly@editTrip');
        Route::post('/user/delete/trip', 'UserApiControllerOnly@deleteTrip');
        Route::get('/user/request/check', 'UserApiControllerOnly@request_status_check');
        //
        Route::get('/user/show/providers', 'UserApiControllerOnly@show_providers');
        //
        Route::post('/user/show/providers', 'UserApiControllerOnly@show_providers');
//	Route::post('/provider-all' , 'UserApiControllerOnly@show_providers');

        // history


        Route::get('/user/trips', 'UserApiControllerOnly@trips');
        Route::get('/user/upcoming/trips', 'UserApiControllerOnly@upcoming_trips');
        Route::post('/user/rate_user', 'UserApiControllerOnly@rateUser');

        Route::get('/user/trip/details', 'UserApiControllerOnly@trip_details');
        Route::get('/user/upcoming/trip/details', 'UserApiControllerOnly@upcoming_trip_details');

//    Route::post('/index', 'ProviderResources\TripControllerApi@index');
        // payment

        Route::post('/user/payment', 'PaymentController@payment'); //was get::

        Route::post('/user/add/money', 'PaymentController@add_money');

        // estimated

        Route::get('/user/test', 'UserApiControllerOnly@test');

        // help

        Route::get('/user/help', 'UserApiControllerOnly@help_details');

        // promocode

        Route::post('/user/promocodes', 'UserApiControllerOnly@promocodes');

        Route::post('/user/promocode/add', 'UserApiControllerOnly@add_promocode');

        // card payment

        Route::resource('/user/card', 'Resource\CardResource');


        Route::get('/providerAll', 'UserApiControllerOnly@providerAll');

    });
});
?>
