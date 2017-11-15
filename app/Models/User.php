<?php

namespace App\Models;

use DB;
use Log;

class User
{
    /*个人信息*/
    public static function get_info($id, $fields = null)
    {
        //TODO $fields 有值过滤返回字段,没有就取所有的
        $query = DB::table('user');
        $query->where(['id'=>$id,'status'=>0]);
        if ($fields) {
            $query->select($fields);
        } else {
            $query->select('id','name','sex','phone','status','age','openid','code','credit_score','is_v','avatar','is_student','nick_name');
        }
        return $query->first();
    }

    /**
     * 根据login_openid获取用户信息
     * @param  [String] $openid  微信的OPENID
     * @return  Object
     */
    public static function get_user_by_openid($openid)
    {
        try {
            $user = DB::table("user")->where('openid', '=', $openid)->first();
            return $user;
        } catch (\Exception $e) {
            Log::info($e);
            return null;
        }
    }

    /**
     * 根据openid获取用户信息
     * @param  [String] $openid  微信的OPENID
     * @return  Object
     */
    public static function get_wx_user_by_openid($openid)
    {
        try {
            $user = DB::table("wx_user")->where("openid", $openid)->first();
            return $user;
        } catch (\Exception $e) {
            Log::info($e);
            return null;
        }
    }

    public static function set_auth($openid){
        DB::table('user')->where('openid',$openid)->update([
            'is_v'=>1,
        ]);
        return true;
    }

}

