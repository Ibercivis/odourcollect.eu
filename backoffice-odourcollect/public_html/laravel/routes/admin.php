<?php 

Route::prefix('admin')->group(function() {
    /*Esentials*/
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/home', 'UserController@home')->name('homeadmin')->middleware('auth:admin');
    Route::get('/logout', 'Auth\AdminLogoutController@logout')->name('logoutadmin');

    /*All*/
    Route::get('/zone/list', 'ZoneController@index')->name('admin.zone.list')->middleware('auth:admin');
	Route::get('/zone/{id}/points', 'ZoneController@points')->name('zone.points')->middleware('auth:admin');
	Route::get('/zone/{id}/interestpoint/{point}', 'ZoneController@zoneInterestPoint')->name('zone.interestpoints')->middleware('auth:admin');
	Route::get('/zone/create', 'ZoneController@create')->name('zone.create')->middleware('auth:admin');
	Route::get('/zone/{id}/delete', 'ZoneController@delete')->name('admin.zone.delete')->middleware('auth:admin');
	Route::get('/zone/{id}/', 'ZoneController@show')->name('zone.show')->middleware('auth:admin');
	Route::get('/zone/{id}/remove/{user}', 'ZoneController@removeUser')->name('admin.zone.remove.user')->middleware('auth:admin');
	Route::get('/zone/{id}/add/{user}', 'ZoneController@addUser')->name('admin.zone.add.user')->middleware('auth:admin');
	Route::get('/zone/active/{id}/', 'ZoneController@active')->name('admin.zone.active')->middleware('auth:admin');
	Route::post('/sendNotificationEmail', 'ZoneController@sendEmail')->name('admin.zone.send.email')->middleware('auth:admin');
	Route::get('/zone/{id}/deleteNotificationZone', 'ZoneController@deleteNotificationZone')->name('admin.zone.delete.notification')->middleware('auth:admin');

	Route::resource('/zone', 'ZoneController');

	Route::get('/user/download', 'UserController@download')->name('admin.user.download')->middleware('auth:admin');
	Route::get('/user/list', 'UserController@index')->name('admin.user.list')->middleware('auth:admin');
	Route::get('/user/create', 'UserController@create')->name('admin.user.create')->middleware('auth:admin');
	Route::post('/user/save', 'UserController@save')->name('admin.user.save')->middleware('auth:admin');
	Route::post('/user/update', 'UserController@update')->name('admin.user.update')->middleware('auth:admin');
	Route::post('/user/updatePassword', 'UserController@updatePassword')->name('user.update.password')->middleware('auth:admin');
	Route::post('/user/updatePermission', 'UserController@updatePermision')->name('user.update.permission')->middleware('auth:admin');
	Route::get('/user/{id}', 'UserController@show')->name('admin.user.show')->middleware('auth:admin');
	Route::get('/user/{id}/email', 'UserController@email')->name('admin.user.email')->middleware('auth:admin');
	Route::post('/sendEmail', 'UserController@sendEmail')->name('user.send.email')->middleware('auth:admin');

	Route::get('/point/list', 'PointOfInterestController@index')->name('point.list')->middleware('auth:admin');
	Route::get('/point/{id}/points', 'PointOfInterestController@points')->name('point.points')->middleware('auth:admin');
	Route::get('/point/create', 'PointOfInterestController@create')->name('point.create')->middleware('auth:admin');
	Route::post('/point/save', 'PointOfInterestController@save')->name('point.save')->middleware('auth:admin');
	Route::post('/point/type/save', 'PointOfInterestController@saveType')->name('point.type.save')->middleware('auth:admin');
	Route::get('/point/type', 'PointOfInterestController@showTypes')->name('point.type')->middleware('auth:admin');
	Route::get('/point/type/{id}/delete', 'PointOfInterestController@deleteType')->name('point.type.delete')->middleware('auth:admin');
	Route::get('/point/{id}/delete', 'PointOfInterestController@delete')->name('point.delete')->middleware('auth:admin');
	Route::get('/point/{id}/', 'PointOfInterestController@show')->name('point.show')->middleware('auth:admin');
	Route::get('/point/{id}/remove/{zone}', 'PointOfInterestController@removeZone')->name('point.remove.zone')->middleware('auth:admin');
	Route::get('/point/{id}/add/{zone}', 'PointOfInterestController@addZone')->name('point.add.zone')->middleware('auth:admin');

	Route::get('/odour/download', 'OdourController@download')->name('odour.admin.download')->middleware('auth:admin');
	Route::get('/odour/list', 'OdourController@index')->name('admin.odour.list')->middleware('auth:admin');
	Route::get('/odour/{id}/verify', 'OdourController@verify')->name('odour.verify')->middleware('auth:admin');
	Route::get('/odour/{id}/verify', 'OdourController@verify')->name('admin.odour.verify')->middleware('auth:admin');
	Route::get('/odour/{id}/comments', 'OdourController@comments')->name('odour.comments')->middleware('auth:admin');
	Route::get('/odour/{id}/markers', 'OdourController@getMarkers')->name('odour.markers');
	Route::get('/odour/zone/{id}/markers', 'OdourController@getZoneMarkers')->name('odour.zone.markers')->middleware('auth:admin');
	Route::get('/odour/unverifiedMarkers', 'OdourController@getUnverifiedMarkers')->name('odour.unverified.markers')->middleware('auth:admin');
	Route::get('/odour/{id}', 'OdourController@show')->name('admin.odour.show')->middleware('auth:admin');
	Route::get('/odour/{id}/spec', 'OdourController@getSpecifications')->name('odour.spec');
	Route::get('/odour/{id}/track', 'OdourController@getTrack')->name('odour.track');
	Route::get('/odourZoneStats', 'OdourController@getOdorZoneStats')->name('odour.zone.stats');
	Route::post('/odour/updateStatus', 'OdourController@updateStatus')->name('odour.update.status')->middleware('auth:admin');
	Route::get('/odour/comment/{id}/hide', 'OdourController@hideComment')->name('odour.comment.hide')->middleware('auth:admin');
	
	Route::get('/odour/track/{id}', 'OdourController@track')->name('admin.odour.track')->middleware('auth:admin');
	Route::get('/statistics', 'OdourController@statistics')->name('statistics')->middleware('auth:admin');
	Route::get('/odourStatistics', 'OdourController@getStatistics')->name('odour.statistics');

	Route::get('/attachOdours', 'OdourController@attach')->name('admin.odour.attach')->middleware('auth:admin');
	Route::get('/uploadcsv', 'OdourController@uploadcsv')->name('admin.odour.uploadcsv')->middleware('auth:admin');
	Route::get('/downloadformat', 'OdourController@downloadformat')->name('admin.odour.format')->middleware('auth:admin');
	Route::post('/importcsv', 'OdourController@importCsv')->name('admin.odour.importcsv')->middleware('auth:admin');
});

 ?>