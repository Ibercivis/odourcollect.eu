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
Auth::routes();

/*Rutas Admin*/
include('admin.php');

Route::get('/logout', 'Auth\LogoutController@logout')->name('logout');

Route::get('/', 'UserController@home')->name('home')->middleware('auth:web');


Route::get('/zone/list', 'ZoneController@index')->name('zone.list')->middleware('auth:web');
	Route::get('/zone/{id}/points', 'ZoneController@points')->name('zone.points');
	Route::get('/zone/{id}/interestpoint/{point}', 'ZoneController@zoneInterestPoint')->name('zone.interestpoints')->middleware('auth:web');
	Route::get('/zone/create', 'ZoneController@create')->name('zone.create')->middleware('auth:web');
	Route::get('/zone/{id}/delete', 'ZoneController@delete')->name('zone.delete')->middleware('auth:web');
	Route::get('/zone/{id}/', 'ZoneController@show')->name('zone.show')->middleware('auth:web');
	Route::get('/zone/{id}/remove/{user}', 'ZoneController@removeUser')->name('zone.remove.user')->middleware('auth:web');
	Route::get('/zone/{id}/add/{user}', 'ZoneController@addUser')->name('zone.add.user')->middleware('auth:web');

	Route::resource('/zone', 'ZoneController');

	Route::get('/user/download', 'UserController@download')->name('user.download')->middleware('auth:web');
	Route::get('/user/list', 'UserController@index')->name('user.list')->middleware('auth:web');
	Route::get('/user/create', 'UserController@create')->name('user.create')->middleware('auth:web');
	Route::post('/user/save', 'UserController@save')->name('user.save')->middleware('auth:web');
	Route::post('/user/update', 'UserController@update')->name('user.update')->middleware('auth:web');
	Route::post('/user/updatePassword', 'UserController@updatePassword')->name('user.update.password')->middleware('auth:web');
	Route::post('/user/updatePermission', 'UserController@updatePermision')->name('user.update.permission')->middleware('auth:web');
	Route::get('/user/{id}', 'UserController@show')->name('user.show')->middleware('auth:web');
	Route::get('/user/{id}/email', 'UserController@email')->name('user.email')->middleware('auth:web');
	Route::post('/sendEmail', 'UserController@sendEmail')->name('user.send.email')->middleware('auth:web');

	Route::get('/point/list', 'PointOfInterestController@index')->name('point.list')->middleware('auth:web');
	Route::get('/point/{id}/points', 'PointOfInterestController@points')->name('point.points')->middleware('auth:web');
	Route::get('/point/create', 'PointOfInterestController@create')->name('point.create')->middleware('auth:web');
	Route::post('/point/save', 'PointOfInterestController@save')->name('point.save')->middleware('auth:web');
	Route::post('/point/type/save', 'PointOfInterestController@saveType')->name('point.type.save')->middleware('auth:web');
	Route::get('/point/type', 'PointOfInterestController@showTypes')->name('point.type')->middleware('auth:web');
	Route::get('/point/type/{id}/delete', 'PointOfInterestController@deleteType')->name('point.type.delete')->middleware('auth:web');
	Route::get('/point/{id}/delete', 'PointOfInterestController@delete')->name('point.delete')->middleware('auth:web');
	Route::get('/point/{id}/', 'PointOfInterestController@show')->name('point.show')->middleware('auth:web');
	Route::get('/point/{id}/remove/{zone}', 'PointOfInterestController@removeZone')->name('point.remove.zone')->middleware('auth:web');
	Route::get('/point/{id}/add/{zone}', 'PointOfInterestController@addZone')->name('point.add.zone')->middleware('auth:web');

	Route::get('/odour/download', 'OdourController@download')->name('odour.download')->middleware('auth:web');
	Route::get('/odour/list', 'OdourController@index')->name('odour.list');
	Route::get('/odour/{id}/verify', 'OdourController@verify')->name('odour.verify')->middleware('auth:web');
	Route::get('/odour/{id}/comments', 'OdourController@comments')->name('odour.comments')->middleware('auth:web');
	Route::get('/odour/{id}/markers', 'OdourController@getMarkers')->name('odour.markers');
	Route::get('/odour/zone/{id}/markers', 'OdourController@getZoneMarkers')->name('odour.zone.markers');
	Route::get('/odour/unverifiedMarkers', 'OdourController@getUnverifiedMarkers')->name('odour.unverified.markers');
	Route::get('/odour/{id}', 'OdourController@show')->name('odour.show')->middleware('auth:web');
	Route::get('/odour/{id}/spec', 'OdourController@getSpecifications')->name('odour.spec');
	Route::get('/odour/{id}/track', 'OdourController@getTrack')->name('odour.track');
	Route::get('/odour/track/{id}', 'OdourController@track')->name('web.odour.track');
	Route::get('/odourZoneStats', 'OdourController@getOdorZoneStats')->name('odour.zone.stats');
	Route::post('/odour/updateStatus', 'OdourController@updateStatus')->name('odour.update.status');
	Route::get('/odour/comment/{id}/hide', 'OdourController@hideComment')->name('odour.comment.hide')->middleware('auth:web');

	Route::get('/statistics', 'OdourController@statistics')->name('statistics')->middleware('auth:web');
	Route::get('/odourStatistics', 'OdourController@getStatistics')->name('odour.statistics');

	Route::get('/attachOdours', 'OdourController@attach')->name('odour.attach')->middleware('auth:web');

	Route::get('/uploadcsv', 'OdourController@uploadcsv')->name('odour.uploadcsv')->middleware('auth:web');
	Route::get('/downloadformat', 'OdourController@downloadformat')->name('odour.format')->middleware('auth:web');
	Route::post('/importcsv', 'OdourController@importCsv')->name('odour.importcsv')->middleware('auth:web');