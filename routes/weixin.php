<?php
Route::group(['prefix' => 'connect'], function () {
	Route::get('/', 'Wx\ConnectController@index');
               Route::get('/noteRecord', 'Wx\ConnectController@note_record');
               Route::get('/msgRecord', 'Wx\CommentController@msg_record');
               Route::get('/msgRemind', 'Wx\CommentController@msg_remind');
               Route::get('/msgRemindScorll', 'Wx\CommentController@msg_remind_scorll');
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
    Route::post('/create_pay_order', 'Wx\TaskController@create_pay_order');//创建订单
});
Route::group(['prefix' => 'main'], function () {
    Route::get('/get_task_list','Wx\TaskController@get_task_list');
});
Route::group(['prefix' => 'task'], function () {
    Route::get('/get_task','Wx\TaskController@get_task');
});

Route::group(['prefix' => 'me'], function () {
    Route::post('/user/info','ProfileController@get_user_info');        //获取用户信息

    Route::post('/feedback/submit','Wx\ProposalController@submit_feedback');    //提交反馈

    Route::post('/complaint/list','Wx\ProposalController@get_complaint_list');   //获取投诉列表
    Route::post('/user_list','Wx\ProposalController@get_user_list');       //获取除自己以外的所有用户
    Route::post('/complaint/submit','Wx\ProposalController@submit_complaint');
    Route::post('/complaint/single','Wx\ProposalController@single_complaint');


});

//账号设置
Route::group(['prefix' => 'set'], function () {
    Route::post('/school','Wx\FollowController@get_school');
});