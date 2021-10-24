<?php

/*
|--------------------------------------------------------------------------
| User Authentication Routes
|--------------------------------------------------------------------------
*/


use Illuminate\Support\Facades\Route;

Route::get('/alo','HomeController@alo');



Route::group(['middleware' => 'guest:web'],function () {
    Route::get('/login', 'AuthUser\UserController@getLogin')->name('login');
    Route::post('/login', 'AuthUser\UserController@login');
    Route::get('/register', 'AuthUser\UserController@getRegisterUser');
    Route::post('/register', 'AuthUser\UserController@register');

    Route::get('/test2','AuthUser\UserController@test2');

    Route::post('/register2_user','AuthUser\UserController@register2');
    Route::post('/register2_provider','ProviderAuth\ProviderController@register2');

});
Route::get('/logout', 'AuthUser\UserController@logout');
Route::post('/logout', 'AuthUser\UserController@logout');


//Auth::routes(['register' => false, 'login' => false]);

Route::get('/reset-user', 'AuthUser\UserController@resetUser')->name('auth.resetUser');
Route::post('/reset-password-user', 'AuthUser\UserController@resetPasswordUser')->name('auth.resetPasswordUser');
Route::get('/new-password-user/{id}/{code}', 'AuthUser\UserController@newPasswordUser')->name('auth.newPasswordUser');
Route::post('/new-password-user/{id}/{code}', 'AuthUser\UserController@postNewPasswordUser')->name('auth.postNewPasswordUser');

Route::get('/user/check/account/{id}/{token}', 'AuthUser\UserController@userCheck')->name('user.auth.accountConfirmation');
Route::post('/check/account/{id}/{token}', 'AuthUser\UserController@check_account');
Route::get('/resend/code/{id}', 'AuthUser\UserController@resend_code')->name('user.resend.code');
Route::get('/check/phone', 'AuthUser\UserController@checkPhone');

/*
|--------------------------------------------------------------------------
| Provider Authentication Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'provider'], function () {
    Route::get('/confirm/car/{provider_car_id}/{provider_id}/{car_id}', 'ProviderAuth\TokenController@confirm_car');
    Route::get('/confirm/car_not/{provider_car_id}/{provider_id}/{car_id}', 'ProviderAuth\TokenController@confirm_car_not');
    Route::get('auth/facebook', 'Auth\SocialLoginController@providerToFaceBook');
    Route::get('auth/google', 'Auth\SocialLoginController@providerToGoogle');

//    Route::get('/login', 'ProviderAuth\LoginController@showLoginForm');
//    Route::post('/login', 'ProviderAuth\LoginController@login');
//    Route::post('/logout', 'ProviderAuth\LoginController@logout');
//
//    Route::get('/register', 'ProviderAuth\RegisterController@showRegistrationForm');
//    Route::post('/register', 'ProviderAuth\RegisterController@register');
Route::group(['middleware' => 'guest:provider'],function () {
    Route::get('/login', 'ProviderAuth\ProviderController@getLogin')->name('provider.login');
    Route::post('/login', 'ProviderAuth\ProviderController@login')->name('provider.post.login');

    Route::get('/register', 'ProviderAuth\ProviderController@getRegister')->name('provider.register');
    Route::post('/register', 'ProviderAuth\ProviderController@register')->name('provider.post.register');

});
    Route::get('/logout', 'ProviderAuth\LoginController@logout');
    Route::post('/logout', 'ProviderAuth\LoginController@logout');


//    Route::get('/test/{id}{id_url}', 'ProviderAuth\ProviderController@test')->name('test');
    Route::get('/register/image/{id}/{token}', 'ProviderAuth\ProviderController@register_image')->name('provider.register.image');
    Route::post('/register/image/{id}/{token}', 'ProviderAuth\ProviderController@post_register_image')->name('provider.post.register.image');

    Route::get('/register/car_type_true/{id}/{token}', 'ProviderAuth\ProviderController@carTypeTrue')->name('carTypeTrue');
    Route::post('/register/car_type_true/post/{id}/{token}', 'ProviderAuth\ProviderController@postCarTypeTrue')->name('postCarTypeTrue');

    Route::get('/register/car_type_false/{id}/{token}', 'ProviderAuth\ProviderController@carTypeFalse')->name('carTypeFalse');
    Route::post('/register/car_type_false/{id}/{token}', 'ProviderAuth\ProviderController@postCarTypeFalse')->name('postCarTypeFalse');

    Route::get('/register/new_car_type_false/{id}/{token}', 'ProviderAuth\ProviderController@newCarTypeFalse')->name('NewCarTypeFalse');
    Route::post('/register/new_car_type_false/{id}/{token}', 'ProviderAuth\ProviderController@postNewCarTypeFalse')->name('postNewCarTypeFalse');

    Route::get('/reset-provider', 'ProviderAuth\ProviderController@resetProvider')->name('auth.resetProvider');
    Route::post('/reset-password-provider', 'ProviderAuth\ProviderController@resetPasswordProvider')->name('auth.resetPasswordProvider');
    Route::get('/new-password-provider/{id}/{code}', 'ProviderAuth\ProviderController@newPasswordProvider')->name('auth.newPasswordProvider');
    Route::post('/new-password-provider/{id}/{code}', 'ProviderAuth\ProviderController@postNewPasswordProvider')->name('auth.postNewPasswordProvider');

    Route::get('/check/account/{id}/{token}', 'ProviderAuth\ProviderController@providerCheck')->name('provider.auth.accountConfirmation');
    Route::post('/check/account/{id}/{token}', 'ProviderAuth\ProviderController@check_account');
    Route::get('/resend/code/{id}', 'ProviderAuth\ProviderController@resend_code')->name('provider.resend.code');

//    Route::post('/password/email', 'ProviderAuth\ForgotPasswordController@sendResetLinkEmail');
//    Route::post('/password/reset', 'ProviderAuth\ResetPasswordController@reset');
//    Route::get('/password/reset', 'ProviderAuth\ForgotPasswordController@showLinkRequestForm');
//    Route::get('/password/reset/{token}', 'ProviderAuth\ResetPasswordController@showResetForm');

    Route::get('/image-upload/{id}/{token}', 'ProviderAuth\ProviderController@provider_image_upload')->name('provider.image.upload');
    Route::post('/image-upload/{id}/{token}', 'ProviderAuth\ProviderController@post_provider_image_upload');

    Route::get('/car/image-upload/{id}', 'ProviderAuth\ProviderController@car_image_upload')->name('provider.car.image.upload');
    Route::post('/car/image-upload/{id}', 'ProviderAuth\ProviderController@post_car_image_upload');

});
/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout');
    Route::get('/logout', 'AdminAuth\LoginController@logout');
    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});
/*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */


Route::get('/dashboard', 'HomeController@index')->name('index');
Route::get('/services/{id}', 'Resource\ServiceResource@frontend')->name('frontend.service');
Route::get('services-transportation/{id}', 'Resource\ServiceResource@servicesTransportation');
Route::group(['prefix'=>'cars'],function(){
    Route::get('/','PagesController@all_index');
    Route::get('/Provinces/{id}','PagesController@Provinces_index');
    Route::get('/Model/{id}','PagesController@Model_index');
    Route::get('/Model-year/{id}','PagesController@year_index');
    Route::get('/Full-type/{id}','PagesController@Full_index');
    Route::get('/Bax-type/{id}','PagesController@Bax_index');
});


// user profiles
Route::get('/profile', 'HomeController@profile');
Route::get('/edit/profile', 'HomeController@edit_profile');
Route::post('/profile', 'HomeController@update_profile');
// update password
Route::get('/change/password', 'HomeController@change_password');
Route::post('/change/password', 'HomeController@update_password');
// ride
Route::get('/confirm/ride', 'RideController@confirm_ride');
Route::post('/create/ride', 'RideController@create_ride');
Route::post('/cancel/ride', 'RideController@cancel_ride');
Route::get('/onride', 'RideController@onride');
Route::post('/payment', 'PaymentController@payment');
Route::post('/rate', 'RideController@rate');
Route::group(['prefix' => 'order','namespace'=>'User' , 'middleware'=>'auth'], function () {
    Route::get('/My-Cars','OrderController@MyCars_index')->name('order.MyCars.index');
    Route::get('/creat','OrderController@get_creat')->name('order.cars.create');
    Route::post('/store','OrderController@store')->name('order.cars.store');
    Route::get('/car/View/{id}','OrderController@view');
    Route::delete('/car/delete/{id}','OrderController@destroy');
    Route::post('/car/active/{id}','OrderController@active');

});
// status check
Route::get('/status', 'RideController@status');
// trips
Route::get('/trips', 'HomeController@trips');
Route::get('/upcoming/trips', 'HomeController@upcoming_trips');
// wallet
Route::get('/wallet', 'HomeController@wallet');
Route::post('/add/money', 'PaymentController@add_money');


// payment
Route::get('/payment', 'HomeController@payment');
// card
// Route::resource('card', 'Resource\CardResource');
// promotions
Route::get('/promotions', 'HomeController@promotions_index')->name('promocodes.index');
Route::post('/promotions', 'HomeController@promotions_store')->name('promocodes.store');


Route::get('auth/facebook', 'Auth\SocialLoginController@redirectToFaceBook');
Route::get('auth/facebook/callback', 'Auth\SocialLoginController@handleFacebookCallback');
Route::get('auth/google', 'Auth\SocialLoginController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\SocialLoginController@handleGoogleCallback');
Route::post('account/kit', 'Auth\SocialLoginController@account_kit')->name('account.kit');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
////// view  Page web
Route::get('/about','PagesController@viewAbout');
Route::get('/privacy','PagesController@viewprivacy');
Route::get('/condition','PagesController@viewCondition');
Route::get('/{lang}','PagesController@lang');


Route::get('/','PagesController@index');
Route::group(['Middlware' => 'setlanguage','middleware' => ['guest:fleet','guest:provider','guest:web']], function () {
    Route::get('/','PagesController@index');


}); //End Middlware


/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'company'], function () {
    Route::group(['middleware' => 'guest:fleet'],function () {
        Route::get('/login', 'Company\LoginController@getLogin');
        Route::post('/login', 'Company\LoginController@login')->name('company.login');

    });

    //Route::post('/logout', 'Company\LoginController@logout')->name('company.logout');
    Route::get('/logout', 'Company\LoginController@logout')->name('company.logout');


    Route::get('/send/email', 'Company\LoginController@getEmail')->name('company.email');
    Route::post('/send/email', 'Company\LoginController@postEmail')->name('company.postEmail');
    Route::get('/password/reset/{id}', 'Company\LoginController@reset')->name('company.reset');
    Route::post('/password/reset/{id}', 'Company\LoginController@resetPassword')->name('company.resetPassword');

    Route::get('/confirm/car/{fleet_id}/{provider_id}/{car_id}', 'ProviderAuth\TokenController@fleet_confirm_car');
    Route::get('/confirm/car_not/{fleet_id}/{provider_id}/{car_id}', 'ProviderAuth\TokenController@fleet_confirm_car_not');

    Route::group(['middleware' => 'fleet'], function () {
        Route::get('/dashboard', 'Company\CompanyController@index')->name('company.index');
        Route::get('/show/car/{id}', 'Company\CompanyController@showCar')->name('company.show.car');
        Route::get('/show/car/provider/{id}', 'Company\CompanyController@showCarProvider')->name('company.show.car.provider');
        Route::get('/show/provider/{id}', 'Company\CompanyController@showProvider')->name('company.show.provider');
        Route::get('/show/provider/car/{id}', 'Company\CompanyController@showProviderCar')->name('company.show.provider.car');

        Route::get('/add-car', 'Company\CompanyController@addCar')->name('company.addCar');
        Route::post('/add-car', 'Company\CompanyController@postAddCar')->name('company.postAddCar');

        Route::get('/add-provider', 'Company\CompanyController@addProvider')->name('company.addProvider');
        Route::post('/add-provider', 'Company\CompanyController@postAddProvider')->name('company.postAddProvider');

        Route::get('/car_type_true/{id}', 'Company\CompanyController@carTypeTrue')->name('company.carTypeTrue');
        Route::post('/car_type_true/{id}', 'Company\CompanyController@postCarTypeTrue')->name('company.postCarTypeTrue');

        Route::get('/car_type_false/{id}', 'Company\CompanyController@carTypeFalse')->name('company.carTypeFalse');
        Route::post('/car_type_false/{id}', 'Company\CompanyController@postCarTypeFalse')->name('company.postCarTypeFalse');

        // Route::delete('remove-car/{id}', 'Company\CompanyController@removeCar')->name('company.removeCar');
        // Route::delete('remove-provider/{id}', 'Company\CompanyController@removeProvider')->name('company.removeProvider');

        // Route::get('/provider/edit/{id}', 'Company\CompanyController@editProvider')->name('company.provider.edit');
        // Route::post('/provider/update/{id}', 'Company\CompanyController@updateProvider')->name('company.provider.update');

        // Route::get('/car/edit/{id}', 'Company\CompanyController@editCar')->name('company.car.edit');
        // Route::post('/company/updateCar/{id}', 'Company\CompanyController@updateCar')->name('company.car.update');

        Route::get('/profile', 'Company\CompanyController@profile')->name('company.profile');
        Route::post('/updateProfile', 'Company\CompanyController@updateProfile')->name('company.profile.update');

        Route::get('/profile/password', 'Company\CompanyController@change_password')->name('company.password');
        Route::post('/change/password', 'Company\CompanyController@update_password')->name('company.password.update');

    });
});


