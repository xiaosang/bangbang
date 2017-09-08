<?php

namespace App\Models\Admin;

use DB;
use Log;

class User
{

    /*登陆判断*/
    public static function get_account($loginName)
    {
        $res = DB::table('admin')
            ->where(['account'=>$loginName,'is_delete'=>0])
            ->first();
        return $res;
    }


    // /*个人信息*/
    // public static function get_info($id)
    // {
    //     //TODO $fields 有值过滤返回字段,没有就取所有的
    //     $query = DB::table('admin');
    //     $query->where(['id'=>$id,'is_delete'=>0]);
    //     if ($fields) {
    //         $query->select($fields);
    //     } else {
    //         $query->select('id','email','account');
    //     }
    //     return $query->first();
    // }

}

