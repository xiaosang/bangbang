<?php

namespace App\Http\Controllers\Wx;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class IndexController extends Controller
{

    public function index()
    {
        //依据角色判断跳转到哪里
        $user = get_wx_user();
        // dd(;
        if($user->is_v==1){
            return view('weixin.index');
        } else {
            return view('weixin.wx');
        }
    }

    public function send(Request $request){
        $code = sendMsg($request->phone);
        if($code == 0){
            return responseToJson(0, '发送成功');
        }else if($code == 2){
            return responseToJson(1, '已经发过');
            
        }else{
            return responseToJson(2, '服务器繁忙');
        }
    }

    public function check(Request $request){
        if(check_msg($request->code)){
            User::set_auth(get_wx_user_openid());
            return responseToJson(0, '认证成功');
        }else{
            return responseToJson(1, '认证失败，验证码错误');
        }
    }


}