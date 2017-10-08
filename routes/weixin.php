<?php
Route::group(['prefix' => 'connect'], function () {
	Route::get('/', 'Wx\ConnectController@index');
});
    Route::post('/release/task','Wx\TaskController@insert');
	Route::post('/setNote', 'Wx\ConnectController@set_note');
	Route::post('/noteUpld', 'Wx\ConnectController@upload');
	Route::get('/getImg', 'Wx\ConnectController@get_img');
	Route::get('/getDetail/{id}', 'Wx\ConnectController@get_detail');
	Route::get('/getMsg/{id}', 'Wx\CommentController@get_msg');
	Route::post('/submitMsg', 'Wx\CommentController@submit_msg');

