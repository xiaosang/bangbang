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
});

//订单
Route::group(['prefix' => 'order', 'middleware' => 'login.check'], function () {
    Route::post('uorder', 'Admin\Order\OrderController@get_uorder_list');
    Route::post('udel', 'Admin\Order\OrderController@uorder_delete');
    Route::post('payorder', 'Admin\Order\OrderController@get_pay_order_list');
    Route::post('paydel', 'Admin\Order\OrderController@pay_order_delete');
});