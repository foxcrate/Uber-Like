<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auto-check-permission'], function () {

    Route::get('/', 'AdminController@dashboard')->name('index');
    Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard'); // ->middleware('permission:حذف المستخدم')
    Route::get('/heatmap', 'AdminController@heatmap')->name('heatmap');
    Route::get('/heatmap/dr', 'AdminController@heatmap2')->name('heatmap.dr');

    Route::get('/translation', 'AdminController@translation')->name('translation');
//Route::get('test',function(){
//    \Spatie\Permission\Models\Permission::create(['name'=>'Show All']);
//});

    Route::group(['as' => 'dispatcher.', 'prefix' => 'dispatcher'], function () {
        Route::get('/', 'DispatcherController@index')->name('index');
        Route::post('/', 'DispatcherController@store')->name('store');
        Route::get('/trips', 'DispatcherController@trips')->name('trips');
        Route::get('/trips/{trip}/{provider}', 'DispatcherController@assign')->name('assign');
        Route::get('/users', 'DispatcherController@users')->name('users');
        Route::get('/providers', 'DispatcherController@providers')->name('providers');
    });


    Route::resource('user', 'Resource\UserResource');
    Route::get('/users/trashed', 'Resource\UserResource@trashed')->name('trashedUser'); // Route Soft Deleted
    Route::get('/users/hdelete/{id}', 'Resource\UserResource@hdelete')->name('hdeleteUser');
    Route::get('/users/restore/{id}', 'Resource\UserResource@restore')->name('restoreUser');

    Route::resource('dispatch-manager', 'Resource\DispatcherResource');
//    Route::resource('account-manager', 'Resource\AccountResource');
    Route::resource('fleet', 'Resource\FleetResource');
     Route::resource('revenues', 'Resource\RevenuesResource');
     Route::get('revenue/Monthly', 'Resource\RevenuesResource@monthly')->name('revenue.monthly');
     Route::get('revenue/ofYears', 'Resource\RevenuesResource@yearly')->name('revenue.yearly');
    Route::get('/fleets/trashed', 'Resource\FleetResource@trashed')->name('trashedFleet'); // Route Soft Deleted
    Route::get('/fleets/hdelete/{id}', 'Resource\FleetResource@hdelete')->name('hdeleteFleet');
    Route::get('/fleets/restore/{id}', 'Resource\FleetResource@restore')->name('restoreFleet');

    Route::post('fleet/wallet', 'Resource\FleetResource@wallet_update')->name('fleet.wallet');

    Route::resource('provider', 'Resource\ProviderResource');
    Route::get('/providers/trashed', 'Resource\ProviderResource@trashed')->name('trashed'); // Route Soft Deleted
    Route::get('/providers/hdelete/{id}', 'Resource\ProviderResource@hdelete')->name('hdelete');
    Route::get('/providers/restore/{id}', 'Resource\ProviderResource@restore')->name('restore');

    Route::post('provider/wallet', 'Resource\ProviderResource@wallet_update')->name('provider.wallet');

    Route::get('/provider/car_type_true/{id}', 'Resource\ProviderResource@carTypeTrue')->name('carTypeTrue');
    Route::post('/provider/car_type_true/{id}', 'Resource\ProviderResource@postCarTypeTrue')->name('postCarTypeTrue');

    Route::get('/provider/car_type_false/{id}', 'Resource\ProviderResource@carTypeFalse')->name('carTypeFalse');
    Route::post('/provider/car_type_false/{id}', 'Resource\ProviderResource@postCarTypeFalse')->name('postCarTypeFalse');

    Route::get('/provider/send_message/{id}', 'Resource\ProviderResource@send_message')->name('send_message');
    Route::post('/provider/send_message/{id}', 'Resource\ProviderResource@post_send_message')->name('post_send_message');

//    Route::resource('document', 'Resource\DocumentResource');
    Route::resource('promocode', 'Resource\PromocodeResource');


    Route::group(['as' => 'provider.'], function () {
        Route::get('review/provider', 'AdminController@provider_review')->name('review');
        Route::get('provider/{id}/approve', 'Resource\ProviderResource@approve')->name('approve');
        Route::get('provider/{id}/disapprove', 'Resource\ProviderResource@disapprove')->name('disapprove');
        Route::get('provider/{id}/banned', 'Resource\ProviderResource@banned')->name('banned');
        Route::get('provider/{id}/request', 'Resource\ProviderResource@request')->name('request');
        Route::get('provider/{id}/statement', 'Resource\ProviderResource@statement')->name('statement');
        Route::resource('provider/{provider}/ProviderResource', 'Resource\ProviderDocumentResource'); // no
        Route::delete('provider/{provider}/service/{document}', 'Resource\ProviderDocumentResource@service_destroy')->name('document.service');

    });

    Route::get('review/user', 'AdminController@user_review')->name('user.review');
    Route::get('user/{id}/request', 'Resource\UserResource@request')->name('user.request');
    Route::post('user/wallet', 'Resource\UserResource@wallet_update')->name('user.wallet');

    Route::get('map', 'AdminController@map_index')->name('map.index');
    Route::get('/map/ajax', 'AdminController@map_ajax')->name('map.ajax');

    Route::get('settings', 'AdminController@settings')->name('settings');
    Route::get('condition_settings', 'AdminController@condition_settings')->name('condition_settings');
    Route::post('condition_settings/store', 'AdminController@condition_settings_store')->name('condition_settings.store');
    Route::post('condition/destroy', 'AdminController@condition_destroy')->name('condition.destroy');

    Route::get('dash_settings', 'AdminController@dash_settings')->name('dash_settings');
    Route::post('settings/store', 'AdminController@settings_store')->name('settings.store');
    Route::post('dash_settings/store', 'AdminController@dash_settings_store')->name('dash_settings.store');

    Route::post('box_settings/store', 'AdminController@box_settings_store')->name('box_settings.store');
    Route::post('box_delete/store', 'AdminController@box_delete')->name('box_delete.delete');

    Route::get('settings/payment', 'AdminController@settings_payment')->name('settings.payment');
    Route::post('settings/payment', 'AdminController@settings_payment_store')->name('settings.payment.store');

    // Route::get('governments', 'AdminController@get_governments')->name('get_governments');
    // Route::post('governments', 'AdminController@post_governments')->name('post_governments');

    Route::get('profile', 'AdminController@profile')->name('profile');
    Route::post('profile', 'AdminController@profile_update')->name('profile.update');

    Route::get('password', 'AdminController@password')->name('password');
    Route::post('password', 'AdminController@password_update')->name('password.update');

    Route::get('payment', 'AdminController@payment')->name('payment');

// statements

    Route::get('/statement', 'AdminController@statement')->name('ride.statement');
    Route::get('/statement/provider', 'AdminController@statement_provider')->name('ride.statement.provider');
    Route::get('/statement/today', 'AdminController@statement_today')->name('ride.statement.today');
    Route::get('/statement/monthly', 'AdminController@statement_monthly')->name('ride.statement.monthly');
    Route::get('/statement/yearly', 'AdminController@statement_yearly')->name('ride.statement.yearly');


// Static Pages - Post updates to pages.update when adding new static pages.

    Route::get('/help', 'AdminController@help')->name('help');
    Route::get('/privacy', 'AdminController@privacy')->name('privacy');
    Route::post('/pages', 'AdminController@pages')->name('pages.update');

    Route::resource('requests', 'Resource\TripResource');
    Route::get('search/requests', 'Resource\TripResource@Searching_Request')->name('requests.search');;
    Route::get('scheduled', 'Resource\TripResource@scheduled')->name('requests.scheduled');

    Route::get('push', 'AdminController@push_index')->name('push.index');
    Route::post('push', 'AdminController@push_store')->name('push.store');
####################################### Request Car Route ###############################################
Route::group(['prefix'=>'cars_order','namespace'=>'Admin'],function (){
    Route::get('/','OrderCarsController@index')->name('order.index');
    Route::get('/create','OrderCarsController@create')->name('order.create');
    Route::post('/create','OrderCarsController@store')->name('order.store');
    Route::get('/edit/{id}','OrderCarsController@edit')->name('order.edit');
    Route::post('/edit/{id}','OrderCarsController@update')->name('order.update');
    Route::post('/active/{id}','OrderCarsController@active')->name('order.active');
    Route::post('/unActive/{id}','OrderCarsController@unActive')->name('order.unActive');
//        Route::get('/view/{id}','OrderCarsController@view')->name('order.view');
    Route::get('/delete/{id}','OrderCarsController@destroy')->name('order.destroy');
});
#######################################End Request Car Route ###############################################

// car class and car model //

    Route::post('/condition/ChangeStatus', 'AdminController@condition_ChangeStatus')->name('condition.Change_Status');
    Route::post('Change_Status', 'AdminController@Change_Status')->name('Change_Status');

    Route::resource('transtype', 'Resource\TransportationTypeResource', ['except' => ['update', 'edit']]);
    Route::get('transtypes', 'Resource\TransportationTypeResource@transtypes')->name('transtypes');

    Route::resource('service', 'Resource\ServiceResource');
    Route::get('services', 'Resource\ServiceResource@services')->name('services');


    Route::resource('carclass', 'Resource\CarClassResource');
    Route::get('carclasses', 'Resource\CarClassResource@CarClasses')->name('carclasses');

    Route::resource('box', 'Resource\BoxResource');
    Route::get('boxes', 'Resource\BoxResource@CarClasses')->name('boxes');

    Route::resource('carmodel', 'Resource\CarModelResource');

    Route::resource('car', 'Resource\CarController');
    Route::get('/car/send_message/{id}', 'Resource\CarController@send_message')->name('car.send_message');
    Route::post('/car/send_message/{id}', 'Resource\CarController@post_send_message')->name('car.post_send_message');

    Route::resource('role', 'AdminAuth\RoleController');
    Route::resource('admin', 'AdminAuth\AdminController');
    Route::resource('permission', 'AdminAuth\PermissionController');
    Route::resource('revenue', 'AdminAuth\RevenueController');
    Route::resource('company-subscription', 'AdminAuth\CompanySubscriptionController');

    Route::resource('day-trip-time', 'Resource\DayTripTimeController');
    Route::post('day-trip-time/statusOpen/{id}', 'Resource\DayTripTimeController@statusOpen')->name('day-trip-time.statusOpen');
    Route::post('day-trip-time/statusClose/{id}', 'Resource\DayTripTimeController@statusClose')->name('day-trip-time.statusClose');

    Route::resource('itinerary', 'Resource\ItineraryController');
    Route::get('/cars', 'Resource\ItineraryController@cars')->name('itinerary.cars');
    Route::post('itinerary/statusOpen/{id}', 'Resource\ItineraryController@statusOpen')->name('itinerary.statusOpen');
    Route::post('itinerary/statusClose/{id}', 'Resource\ItineraryController@statusClose')->name('itinerary.statusClose');


    Route::resource('station', 'Resource\StationController');
    Route::resource('city', 'CityController');
    Route::resource('governorate', 'GovernorateController');
    Route::post('/governorate/changeStatus', 'GovernorateController@changeStatus')->name('governorate.changeStatus');
///AdminLang
    Route::get('lang/{lang}','PagesController@AdminLang');
});
