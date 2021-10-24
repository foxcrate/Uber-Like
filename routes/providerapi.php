<?php

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
Route::group(['middleware' => ['language']], function () {
    Route::post('/register', 'ProviderAuth\TokenController@register');
    Route::get('/cars', 'ProviderResources\ProfileController@cars');
    Route::post('/oauth/token', 'ProviderAuth\TokenController@authenticate');
    Route::post('/logout', 'ProviderAuth\TokenController@logout');
    Route::post('/auth/facebook', 'ProviderAuth\TokenController@facebookViaAPI');
    Route::post('/auth/google', 'ProviderAuth\TokenController@googleViaAPI');
    Route::post('/forgot/password', 'ProviderAuth\TokenController@forgot_password');
    Route::post('/reset/password', 'ProviderAuth\TokenController@reset_password');
    Route::post('/app_details', 'ProviderResources\ProfileController@App_Details');
    Route::get('/account_ext', 'ProviderResources\ProfileController@checkAccount');
    Route::get('/active_email', 'ProviderResources\ProfileController@activeEmail');
    Route::get('/fawzy_active_email', 'ProviderResources\ProfileController@fawzyActiveEmail');
    Route::get('/check_email', 'ProviderResources\ProfileController@checkEmail');
    Route::get('/get_my_cars', 'ProviderResources\ProfileController@mycars');

    Route::get('/check_phone', 'ProviderResources\ProfileController@checkPhone');
    Route::post('/check_email_phone', 'ProviderResources\ProfileController@checkEmailPhone');
    Route::get('/verification_email', 'ProviderResources\ProfileController@verificationEmail');
    Route::get('/fawzy_verification_email', 'ProviderResources\ProfileController@fawzyVerificationEmail');
    Route::post('/image_upload', 'ProviderResources\ProfileController@imageUpload');
    Route::post('/car_type_true', 'ProviderAuth\TokenController@CarTypeTrue');
    Route::post('/car_type_false', 'ProviderAuth\TokenController@CarTypeFalse');
    Route::post('/car_image_update', 'ProviderAuth\TokenController@update_image');
//    Route::post('request_payment', 'ProviderResources\ProfileController@requestPayment');
    Route::post('create_trip_payment', 'ProviderResources\ProfileController@createRequestPayment');

    Route::post('calculate_payment', 'ProviderResources\ProfileController@calculatePayment');

    Route::post('droppedBill', 'ProviderResources\ProfileController@droppedBill');

    Route::get('get_andriod_version', 'ProviderResources\ProfileController@get_andriod_version');

    Route::post('post_andriod_version', 'ProviderResources\ProfileController@post_andriod_version');

    Route::post('alo', 'ProviderResources\ProfileController@alo');


    Route::post('trip_payment', 'ProviderResources\ProfileController@updateRequestPayments');
    Route::post('user_tax', 'ProviderResources\ProfileController@userTax');
    Route::post('user_tax2', 'ProviderResources\ProfileController@userTax2');
    Route::group(['middleware' => ['provider.api']], function () {
        Route::post('/refresh/token', 'ProviderAuth\TokenController@refresh_token');
        Route::post('/car_service', 'ProviderResources\ProfileController@carService');
        Route::post('rate_provider', 'ProviderResources\ProfileController@rateProvider');

        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', 'ProviderResources\ProfileController@index');
            Route::post('/', 'ProviderResources\ProfileController@update');

            Route::post('/newDetails', 'ProviderResources\ProfileController@newDetails');
            Route::post('/password', 'ProviderResources\ProfileController@password');
            Route::post('/location', 'ProviderResources\ProfileController@location');
            Route::post('/available', 'ProviderResources\ProfileController@available');
            Route::post('/update_location', 'ProviderResources\ProfileController@updateLocation');
        });
        Route::get('/target', 'ProviderResources\ProfileController@target');
        Route::post('cancel', 'ProviderResources\TripControllerApi@cancel');
        Route::post('accepted', 'ProviderResources\TripControllerApi@accepted');
        //Route::post('index', 'ProviderResources\TripControllerApi@index');
        Route::post('summary', 'ProviderResources\TripControllerApi@summary');
        Route::get('help', 'ProviderResources\TripControllerApi@help_details');
        Route::resource('trip', 'ProviderResources\TripControllerApi');
        Route::group(['prefix' => 'trip'], function () {
            Route::post('waypoint', 'ProviderResources\TripControllerApi@WayPoint');
            Route::post('{id}', 'ProviderResources\TripControllerApi@accept');
            Route::post('{id}/rate', 'ProviderResources\TripControllerApi@rate');
            Route::post('{id}/message', 'ProviderResources\TripControllerApi@message');
        });
        Route::group(['prefix' => 'requests'], function () {
            Route::get('/upcoming', 'ProviderResources\TripControllerApi@scheduled');
            Route::get('/history', 'ProviderResources\TripControllerApi@history');
            Route::get('/history/details', 'ProviderResources\TripControllerApi@history_details');
            Route::get('/upcoming/details', 'ProviderResources\TripControllerApi@upcoming_details');
        });
    });
});
