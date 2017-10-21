<?php
Route::group(['prefix' => 'connect'], function () {
	Route::get('/', 'Wx\ConnectController@index');
       Route::get('/userInfo', 'Wx\ConnectController@user_info');
       Route::get('/noteRecord', 'Wx\ConnectController@note_record');
       Route::post('/readMsg', 'Wx\CommentController@read_msg');
       Route::get('/msgRecord', 'Wx\CommentController@msg_record');
       Route::get('/msgRemind', 'Wx\CommentController@msg_remind');
       Route::get('/msgRemindScorll', 'Wx\CommentController@msg_remind_scorll');
	Route::post('/setNote', 'Wx\ConnectController@set_note');
	Route::post('/noteUpld', 'Wx\ConnectController@upload');
	Route::get('/getImg', 'Wx\ConnectController@get_img');
	Route::get('/getDetail/{id}', 'Wx\ConnectController@get_detail');
	Route::get('/getMsg/{id}', 'Wx\CommentController@get_msg');
	Route::post('/submitMsg', 'Wx\CommentController@submit_msg');
        Route::post('/deleteMsg', 'Wx\CommentController@delete_msg');
        Route::post('/deleteNote', 'Wx\ConnectController@delete_note');
        Route::get('/msgCount', 'Wx\CommentController@msg_count');//得要用户未读消息的数量
});
Route::group(['prefix' => 'release'], function () {
    Route::post('/issue_task', 'Wx\TaskController@issue_task');
    Route::get('/get_user_info','Wx\TaskController@get_user_info');
    Route::get('/get_address_list','Wx\TaskController@get_address_list');
    Route::post('/create_pay_order', 'Wx\TaskController@create_pay_order');//创建订单
    Route::get('/get_task_info', 'Wx\TaskController@get_task_info');//任务详情
});
Route::group(['prefix' => 'main'], function () {
    Route::get('/get_task_list','Wx\TaskController@get_task_list');
    Route::get('/get_announcementContent','Wx\TaskController@get_announcementContent');
});
Route::group(['prefix' => 'task'], function () {
    Route::get('/get_task','Wx\TaskController@get_task');
    Route::post('/accept_task','Wx\TaskController@accept_task');
});
Route::group(['prefix' => 'announcement'], function () {
    Route::get('/get_announcement_list','Wx\TaskController@get_announcement_list');
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
    Route::get('/get_check','Wx\FollowController@get_check');
    Route::post('/login','Wx\FollowController@login');
    Route::post('/get_info','Wx\FollowController@get_info');
});

