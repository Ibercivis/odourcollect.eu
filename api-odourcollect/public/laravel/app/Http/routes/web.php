<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('odour-zones', 'OdorController@zoneAttach');
Route::get('points-zones', 'ZoneController@zoneAttachPoints');

Route::get('user/verify/{verification_code}', 'AuthController@verifyUser');
Route::get('password/reset/ok', 'AuthController@passowrdResetOK')->name('password.reset.ok');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@postReset')->name('password.reset');
Route::get('/prolor-info', 'OdorController@prolorInformation')->name('odor.prolor');
Route::get('/attachOdourToZone', 'OdorController@attachOdourToZone')->name('odour.attach.zone');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
