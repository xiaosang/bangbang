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

    public static function get_oreder_num($time,$yet_time){
        $order=[];
        $finsih = [];
        $all = [];
        $finsih['yet_num'] = DB::table('transaction_order')
                            ->where('create_time','>',$yet_time)
                            ->where('create_time','<',$time)
                            ->where('is_delete',0)
                            ->where('status',2)
                            ->selectRaw('count(id) as count')
                            ->get();
        $finsih['day_num'] = DB::table('transaction_order')
                            ->where('is_delete',0)
                            ->where('create_time','>',$time)
                            ->where('status',2)
                            ->selectRaw('count(id) as count')
                            ->get();
        $finsih['all_num'] = DB::table('transaction_order')
                            ->where('is_delete',0)
                            ->where('status',2)
                            ->selectRaw('count(id) as count')
                            ->get();
        $all['yet_num'] = DB::table('transaction_order')
                            ->where('create_time','>',$yet_time)
                            ->where('create_time','<',$time)
                            ->where('is_delete',0)
                            ->selectRaw('count(id) as count')
                            ->get();
        $all['day_num'] = DB::table('transaction_order')
                            ->where('is_delete',0)
                            ->where('create_time','>',$time)
                            ->selectRaw('count(id) as count')
                            ->get();
        $all['all_num'] = DB::table('transaction_order')
                            ->where('is_delete',0)
                            ->selectRaw('count(id) as count')
                            ->get();
        $order['finish'] = $finsih;
        $order['all'] = $all;
        return $order;
    }


}
