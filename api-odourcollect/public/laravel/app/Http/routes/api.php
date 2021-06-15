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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('recover', 'AuthController@recover');

//Points Of Interest Routes
Route::post('/points-of-interest/list', 'PointOfInterestController@index')->name('interest.index');

//Odor Routes
Route::get('/odor/list', 'OdorController@index')->name('odor.index');
Route::get('/odor/listretro', 'OdorController@retro')->name('odor.retro');
Route::post('/odor/list', 'OdorController@index')->name('odor.index');
Route::get('/odor/{odor}', 'OdorController@show')->name('odor.show');
Route::get('/odor/{odor}/comments', 'OdorController@comments')->name('odor.comments');

//Odor Annoy's
Route::get('/odor-annoy', 'OdorAnnoyController@index')->name('odor.annoy.index');
//Odor Durations's
Route::get('/odor-duration', 'OdorDurationController@index')->name('odor.duration.index');
//Odor Type's
Route::get('/odor-type', 'OdorTypeController@index')->name('odor.type.index');
//Odor Intensity's
Route::get('/odor-intensity', 'OdorIntensityController@index')->name('odor.intensity.index');

//Zone Routes
Route::get('/zone/list', 'ZoneController@index')->name('zone.index');
Route::get('/zone/{zone}', 'ZoneController@show')->name('zone.show');
Route::get('/attachOdourToZone', 'OdorController@attachOdourToZone')->name('odour.attach.zone');

Route::get('contact', 'ContactController@store')->name('contact.store');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('user/{id}', 'UserController@show')->name('user.profile');
    Route::post('user/{id}', 'AuthController@profile')->name('user.update');
    Route::put('user/{id}/delete', 'AuthController@delete')->name('user.delete');
    Route::post('user/{id}/password', 'AuthController@password')->name('user.password');
    Route::get('user/{id}/odours', 'UserController@odours')->name('user.odours');
    Route::get('user/{id}/zones', 'UserController@zones')->name('user.zones');

    Route::post('/odor', 'OdorController@store')->name('odor.store');
    Route::put('/odor/{odor}', 'OdorController@update')->name('odor.update');
	Route::delete('/odor/{odor}', 'OdorController@destroy')->name('odor.destroy');
    Route::get('/odor/{odor}/is-confirmed/{user}', 'OdorController@isConfirmed')->name('odor.is.confirmed');
    Route::get('/odor/{odor}/confirm/{user}', 'OdorController@confirm')->name('odor.confirm');
    Route::get('/odor/{odor}/unconfirm/{user}', 'OdorController@unconfirm')->name('odor.unconfirm');
    Route::post('/comment/store', 'CommentController@store')->name('comment.store');
    Route::put('/comment/{id}/hide', 'CommentController@hide')->name('comment.hide');
    Route::put('/odor/{odor}/delete', 'OdorController@delete')->name('odor.delete');


});
