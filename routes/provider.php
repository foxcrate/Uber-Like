<?php

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ProviderController@index')->name('index');
Route::get('/trips', 'ProviderResources\TripController@history')->name('trips');

Route::get('/incoming', 'ProviderController@incoming')->name('incoming');
Route::post('/request/{id}', 'ProviderController@accept')->name('accept');
Route::patch('/request/{id}', 'ProviderController@update')->name('update');
Route::post('/request/{id}/rate', 'ProviderController@rating')->name('rating');
Route::delete('/request/{id}', 'ProviderController@reject')->name('reject');

Route::get('/earnings', 'ProviderController@earnings')->name('earnings');
Route::get('/upcoming', 'ProviderController@upcoming_trips')->name('upcoming');
Route::post('/cancel', 'ProviderController@cancel')->name('cancel');

// Create New Car -> Provider
Route::get('/new_car', 'ProviderAuth\ProviderController@newCar')->name('newCar');
Route::post('/new_car', 'ProviderAuth\ProviderController@postNewCar')->name('postNewCar');

//Route::resource('documents', 'ProviderResources\DocumentController');

// Route::get('/documents', 'ProviderAuth\MenuProviderController@documents')->name('documents');
// Route::post('/documents', 'ProviderAuth\MenuProviderController@postDocuments')->name('post.documents');
Route::get('/cars', 'ProviderAuth\MenuProviderController@cars')->name('cars');
Route::get('/show-car/{id}', 'ProviderAuth\MenuProviderController@showCar')->name('showCar');

// Route::get('/edit-car/{id}', 'ProviderAuth\MenuProviderController@editCar')->name('editCar');
// Route::post('/update-car/{id}', 'ProviderAuth\MenuProviderController@updateCar')->name('updateCar');




Route::get('/profile', 'ProviderResources\ProfileController@show')->name('profile.index');
Route::post('/profile', 'ProviderResources\ProfileController@store')->name('profile.update');



Route::get('/location', 'ProviderController@location_edit')->name('location.index');
Route::post('/location', 'ProviderController@location_update')->name('location.update');

Route::get('/profile/password', 'ProviderController@change_password')->name('change.password');
Route::post('/change/password', 'ProviderController@update_password')->name('password.update');

Route::post('/profile/available', 'ProviderController@available')->name('available');
