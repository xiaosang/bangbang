<?php
Route::group(['prefix' => 'connect'], function () {
	Route::post('/setNote', 'Wx\ConnectController@set_note');
	Route::get('/', 'Wx\ConnectController@index');
});