<?php

Route::group(['prefix' => 'admin'], function () {
    Route::post('super/password', 'Admin\SuperController@password');    //修改密码

    Route::get('/menu/get', 'Admin\MenuController@get_menu');
    Route::group(['prefix' => 'user'], function () {
    	Route::post("/list", 'Admin\UserController@get_list');
        Route::post("/status", 'Admin\UserController@edit_status');
    });
    Route::group(['prefix' => 'weixin'], function () {
        Route::post("/config/set", 'Admin\WeixinController@set_config');
        Route::post("/config/get", 'Admin\WeixinController@get_config');

        Route::post("/template/set", 'Admin\WeixinController@set_wx_template');
        Route::post("/template/get", 'Admin\WeixinController@get_wx_template');

        Route::post("/reply/get", 'Admin\WeixinController@get_reply');
        Route::post("/reply/set", 'Admin\WeixinController@set_reply');
        Route::post("/follow/set", 'Admin\WeixinController@set_follow');
        Route::post("/news/set", 'Admin\WeixinController@set_news');

        Route::post("/reply/create", 'Admin\WeixinController@create_reply');
        Route::post("/reply/delete/{id}", 'Admin\WeixinController@delete_reply');

        Route::post("/industry", 'Admin\WeixinController@set_industry');
        Route::post("/update", 'Admin\WeixinController@add_template');
        Route::post("/auto_update", 'Admin\WeixinController@get_private_templates');
        Route::post("/menu/get", 'Admin\WeixinController@get_menu');
        Route::post("/menu/set", 'Admin\WeixinController@set_menu');

        Route::get('/group/get', 'Admin\WeixinController@get_wx_group');
        Route::get('/group/set', 'Admin\WeixinController@set_wx_group');
        Route::get('/group/move', 'Admin\WeixinController@move_wx_group');
        Route::get('/group/del', 'Admin\WeixinController@del_wx_group');
        Route::get('/group/menu/a/set', 'Admin\WeixinController@set_wx_group_menu_a');
        Route::get('/group/menu/b/set', 'Admin\WeixinController@set_wx_group_menu_b');
        Route::get('/menu/get', 'Admin\WeixinController@get_wx_menu');
        Route::get('/user/menu/get', 'Admin\WeixinController@get_wx_user_mennu');
    });

    //任务
    Route::group(['prefix' => 'task'], function () {
        Route::post('list', 'Admin\Task\TaskController@get_list');
        Route::post('del', 'Admin\Task\TaskController@task_delete');
        Route::post('over', 'Admin\Task\TaskController@get_over_list');
        Route::post('evaluate', 'Admin\Task\TaskController@get_evaluate');
    });

    //订单
    Route::group(['prefix' => 'order'], function () {
        Route::post('uorder', 'Admin\Order\OrderController@get_uorder_list');
        Route::post('udel', 'Admin\Order\OrderController@uorder_delete');
        Route::post('payorder', 'Admin\Order\OrderController@get_pay_order_list');
        Route::post('paydel', 'Admin\Order\OrderController@pay_order_delete');
    });

    //首页统计信息
    Route::group(['prefix' => 'index'], function () {
        Route::post('task', 'Admin\Index\IndexController@get_task');
        Route::post('user', 'Admin\Index\IndexController@get_new_add_user');
        Route::post('order', 'Admin\Index\IndexController@get_order');
        Route::post('ordernum', 'Admin\Index\IndexController@get_order_num');
    });

    Route::group(['prefix' => 'forum'], function () {
        Route::post('note', 'Admin\Forum\NoteController@get_list');
        Route::post('comment', 'Admin\Forum\NoteController@get_comment');
        Route::post('commentdel', 'Admin\Forum\NoteController@comment_delete');
        Route::post('notedel', 'Admin\Forum\NoteController@note_delete');
        // Route::post('user', 'Admin\Index\IndexController@get_new_add_user');
    });
});
