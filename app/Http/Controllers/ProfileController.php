<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Response;
use Log;
use Redirect;

class ProfileController extends Controller
{
    /**
     * 微信端获取个人信息方法
     * @return \Illuminate\Http\JsonResponse
     */
    function get_user_info(){
        $user_id = get_wx_user_id();
        $user_info = User::get_info($user_id);
        if($user_info){
            return responseToJson(1,'获取成功',$user_info);
        }else{
            return responseToJson(0,'获取失败');
        }
    }

}