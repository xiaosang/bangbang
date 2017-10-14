<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Wechat Routes
|--------------------------------------------------------------------------
|
*/

Route::any('/', 'Wx\WechatController@serve');
Route::any('/re', function(){
	echo "string";
});

