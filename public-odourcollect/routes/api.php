<?php

//Acount Routes
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('recover', 'UserController@recover');

//User Routes
Route::post('user/{id}', 'UserController@show')->name('user.profile');
Route::post('user/update/{id}', 'UserController@update')->name('user.update');
Route::post('user/password/{id}', 'UserController@password')->name('user.password');

Route::post('user/{id}/odours', 'UserController@odours')->name('user.odours');
Route::post('user/{id}/zones', 'UserController@zones')->name('user.zones');
Route::post('comment/store', 'UserController@storeComment')->name('comment.store');
Route::post('/contact', 'UserController@sendContact')->name('contact');


//Points Of Interest Routes
Route::post('/points-of-interest/list', 'PointOfInterestController@index')->name('interest.index');

//Odor Routes
Route::post('/odor/list', 'OddourController@index')->name('odor.index');
Route::get('/odor/{odor}', 'OddourController@show')->name('odor.show');
Route::post('/odor/{odor}/comments', 'OddourController@comments')->name('odor.comments');
Route::post('/odor/{odor}/delete', 'OddourController@delete')->name('odor.delete');

Route::post('/odor/store', 'OddourController@store')->name('odor.store');
Route::post('/odor/update/{odor}', 'OddourController@update')->name('odor.update');

//Route::get('/odor/{odor}/confirm/{user}', 'OddourController@confirm')->name('odor.confirm');
Route::post('/odor/confirm', 'OddourController@confirm')->name('odor.confirm');
Route::post('/odor/{odor}/unconfirm/{user}', 'OddourController@unconfirm')->name('odor.unconfirm');
Route::post('odor/{odor}/is-confirmed/{id}', 'OddourController@isConfirmed')->name('odor.confirmed');


//Odor Annoy's List
Route::get('/odor/properties/annoy', 'OddourController@annoy')->name('odor.list.annoy');
//Odor Durations's List
Route::get('/odor/properties/duration', 'OddourController@duration')->name('odor.list.duration');
//Odor Type's List
Route::get('/odor/properties/type', 'OddourController@type')->name('odor.list.type');
//Odor Intensity's List
Route::get('/odor/properties/intensity', 'OddourController@intensity')->name('odor.list.intensity');

//Zone Routes
Route::get('/zone/list', 'ZoneController@index')->name('zone.index');
Route::post('/zone/{zone}', 'ZoneController@show')->name('zone.show');

Route::post('/comment/{id}/hide', 'CommentController@hide')->name('comment.hide');

