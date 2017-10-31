<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Index extends Model
{

    /*
        统计订单的状态
    */
    public static function get_task_status(){
        return DB::table('task')->where('is_delete','=',0)->groupBy('status')->selectRaw('status,count(id) as count')->get();
    }
    /*
        统计昨天新增的会员数和今天新增的会员数和总共关注多少
    */
    public static function get_new_add_user($time,$yet_time){
        return DB::table('wx_user')
                ->where('is_subscribe','=',1)
                ->where('subscribe_time','>',$yet_time)
                ->where('subscribe_time','<',$time)
                ->selectRaw('count(id) as count')
                ->get();
    }

    public static function get_all_user(){
        return DB::table('wx_user')
                ->where('is_subscribe','=',1)
                ->selectRaw('count(id) as count')
                ->get();
    }

    public static function get_order(){
        return DB::table('transaction_order')->where('is_delete',0)->get();
    }
}
