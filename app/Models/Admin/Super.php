<?php

namespace App\Models\Admin;

use DB;
use Log;

class Super
{

    /*登陆判断*/
    public static function get_account($loginName)
    {
        $res = DB::table('admin')
            ->where(['account'=>$loginName])
            ->first();
        return $res;
    }

    static function is_password_right($pwd)
    {
        $user = DB::table('admin')->where('id', get_session_user_id())->first();
        return $user->password == encrypt_password($pwd);
    }
    static function change_password($new_pwd)
    {
        $password = encrypt_password($new_pwd);
        $res = DB::table('admin')
            ->where('id', get_session_user_id())
            ->update(['password' => $password, 'update_time' => time()]);
        return $res;
    }

}

