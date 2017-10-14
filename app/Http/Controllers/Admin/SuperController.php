<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Admin\Role;
use App\Models\WxLogin;

use Response;
use DB;

class SuperController extends Controller
{

    function password(Request $resquest){
        $old_pwd = $resquest->old_pwd;
        $new_pwd = $resquest->new_pwd;
        $re_pwd = $resquest->re_pwd;

        if (strlen($new_pwd) < 5 || strlen($new_pwd) > 20) {
            return Response::json(['status' => 0, 'msg' => '密码长度需在5~20位']);
        }
        if ($new_pwd != $re_pwd) {
            return Response::json(['status' => 0, 'msg' => '新密码与重复密码不同']);
        }

        if(!User::is_password_right($old_pwd)){
            return Response::json(['status' => 0, 'msg' => '原密码输入错误']);
        }

        $res = User::change_password($new_pwd);
        if ($res !== false) {
            return Response::json(['status' => 1, 'msg' => '更改成功']);
        } else {
            return Response::json(['status' => 0, 'msg' => '更改失败，请重试']);
        }
    }
}
