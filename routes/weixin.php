<?php
Route::group(['prefix' => 'connect'], function () {
	Route::get('/', 'Wx\ConnectController@index');
	Route::post('/setNote', 'Wx\ConnectController@set_note');
	Route::post('/noteUpld', 'Wx\ConnectController@upload');
	Route::get('/getImg', 'Wx\ConnectController@get_img');
	Route::get('/getDetail/{id}', 'Wx\ConnectController@get_detail');
	Route::get('/getMsg/{id}', 'Wx\CommentController@get_msg');
	Route::post('/submitMsg', 'Wx\CommentController@submit_msg');
});
Route::group(['prefix' => 'release'], function () {
    Route::post('/issue_task', 'Wx\TaskController@issue_task');
    Route::get('/get_user_info','Wx\TaskController@get_user_info');
    Route::get('/get_address_list','Wx\TaskController@get_address_list');
});
Route::group(['prefix' => 'main'], function () {
    Route::get('/get_task_list','Wx\TaskController@get_task_list');
});

Route::group(['prefix' => 'me'], function () {
    Route::post('/user/info','ProfileController@get_user_info');        //获取用户信息
    Route::post('/feedback/submit','Wx\ProposalController@submit_feedback');



});
