<?php

namespace App\Http\Controllers\Wx;

use App\Models\User;
use App\Http\Controllers\Controller;
class IndexController extends Controller
{

    public function index()
    {
        //依据角色判断跳转到哪里
        // $user = get_wx_user();
    //   if($user){
          return view('weixin.index');
        // } else {
            // return '<h1>未知错误</h1>';
        // }
    }

}