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

Route::group(['prefix' => 'task', 'middleware' => 'login.check'], function () {
    Route::post('list', 'Admin\Task\TaskController@get_list');
});