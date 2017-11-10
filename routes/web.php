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


Route::get('/show_img','IndexController@show_img');