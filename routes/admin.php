<?php

Route::group(['prefix' => 'admin', 'middleware' => 'login.check'], function () {
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
    Route::group(['prefix' => 'notice'], function () {
        Route::post('list', 'Admin\Notice\IndexController@get_list');
        Route::post('del', 'Admin\Notice\IndexController@notice_delete');
        Route::get('get', 'Admin\Notice\IndexController@get');
        Route::post('edit', 'Admin\Notice\IndexController@edit');
    });
    //失物招领
    Route::group(['prefix' => 'loafo'], function () {
        Route::post('list', 'Admin\Loafo\IndexController@get_list');
        Route::post('del', 'Admin\Loafo\IndexController@loafo_delete');
    });
});
