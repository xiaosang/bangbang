<?php

namespace App\Models\Wx;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class News
 * @package App\Models\Wx
 * 订单消息模型
 */
class News extends Model
{
    /**
     * @param $user_id
     * @return bool
     * 检测某用户是否有未读的消息
     */
    static function check_unread($user_id){
        $result = DB::table('message')->where(['user_id'=>$user_id,'is_delete'=>0,'is_read'=>0])->first();
        if(count($result)>0){
            return true;
        }
        return false;
    }

    /**
     * 获取消息列表
     */
    static function news_list($user_id,$unread,$page,$num){
        $start = $page*$num;
        $q = DB::table('message')->where(['user_id'=>$user_id])->orderBy('create_time','desc')->offset($start)->limit($num);
        if($unread==0||$unread==1){
            $q->where(['unread'=>$unread]);
        }
        $result = $q->get();
        return $result;
    }

    /**
     * 将列表显示出来的消息如果是未读状态的话，将其改为已读状态
     */
    static function change_unread_to_read($user_id,$page,$num){
        $start = $num*$page;
        DB::table('message')->where(['user_id'=>$user_id])->orderBy('create_time','desc')->offset($start)->limit($num)
            ->update(['is_read'=>1,'view_time'=>time()]);
    }

}