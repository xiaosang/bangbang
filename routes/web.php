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
Route::get("/login", 'LoginController@index');
Route::post("/login", 'LoginController@login');
Route::any("/logout", 'LoginController@logout');
Route::any("/wechat", 'Wx\WechatController@serve');
Route::any("/menu_add",'Wx\WechatController@menu_add');
Route::get('/test', function(){
    return view('test.index');
});
//Route::get('wx/', function () {
//    return view('index');
//});

//测试专用
Route::get('/test', 'IndexController@test');

Route::group(['middleware' => ['login.check']], function () {
	Route::get('/', 'IndexController@index');
	 include('admin.php');

});

Route::group(['middleware' => ['wechat.binding'],'prefix'=>'wx'], function () {

    Route::get('/', 'Wx\IndexController@index');
    include('weixin.php');
});

include('admin.php');


//任务
Route::group(['prefix' => 'task', 'middleware' => 'login.check'], function () {
    Route::post('list', 'Admin\Task\TaskController@get_list');
    Route::post('del', 'Admin\Task\TaskController@task_delete');
    Route::post('over', 'Admin\Task\TaskController@get_over_list');
    Route::post('evaluate', 'Admin\Task\TaskController@get_evaluate');
});

//订单
Route::group(['prefix' => 'order', 'middleware' => 'login.check'], function () {
    Route::post('uorder', 'Admin\Order\OrderController@get_uorder_list');
    Route::post('udel', 'Admin\Order\OrderController@uorder_delete');
    Route::post('payorder', 'Admin\Order\OrderController@get_pay_order_list');
    Route::post('paydel', 'Admin\Order\OrderController@pay_order_delete');
});

//首页统计信息
Route::group(['prefix' => 'index', 'middleware' => 'login.check'], function () {
    Route::post('task', 'Admin\Index\IndexController@get_task');
    Route::post('user', 'Admin\Index\IndexController@get_new_add_user');
});

Route::group(['prefix' => 'forum', 'middleware' => 'login.check'], function () {
    Route::post('note', 'Admin\Forum\NoteController@get_list');
    Route::post('comment', 'Admin\Forum\NoteController@get_comment');
    Route::post('commentdel', 'Admin\Forum\NoteController@comment_delete');
    Route::post('notedel', 'Admin\Forum\NoteController@note_delete');
    // Route::post('user', 'Admin\Index\IndexController@get_new_add_user');
});