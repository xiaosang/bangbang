<?php

namespace App\Models\Wx;
use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Task extends Model
{

    static public function index(){}

    /*  得到用户收货地址列表
        */
    static public function get_address_list($user_id){
        $result = DB::table('address')
            ->where('create_user_id',$user_id)
            ->where('is_delete',0)
            ->get();
        return $result;
    }

    /*发布任务
    $name,标题
    $content,内容
    $type,类型（0.有偿  1.无偿）
    $pay_money,支付金额（默认0）
    $task_finish_time,任务完成时间
    $expected_time,截止时间
    $user_name 雇主姓名
    $user_phone 联系电话
    $address_name 收货地址
    $is_hide是否匿名(0.不匿名1.匿名)
    $create_user_id 创建任务ID
    $key 密钥
    */
    static public function issue_task($name,$content,$type,$pay_money=0,$task_finish_time,$expected_time,$user_name,$user_phone,$address_name,$is_hide,$create_user_id,$key){

        $data = [
            'type'=>$type,
            'name'=>$name,
            'content'=>$content,
            'create_time'=>time(),
            'expected_time'=>$expected_time,
            'task_finish_time'=>$task_finish_time,
            'create_user_id'=>$create_user_id,
            'user_name'=>$user_name,
            'user_phone'=>$user_phone,
            'pay_money'=>$pay_money,
            'is_hide'=>$is_hide,
            'key'=>$key,
            'address_name'=>$address_name
        ];

        $result = DB::table('task')
            ->insertGetId($data);

        return $result;
    }
    /*得到最新num条任务信息
    $start 开始条数
    $num 条数
    $type 任务类型 -1全部 0有偿 1无偿
    $time 创建时间
    */
    static public function get_task_list($start,$num,$type,$time='-1'){
        if($type == -1){
            $type = [0,1];
        }else if($type == 0){
            $type = [0];
        }else if($type == 1){
            $type = [1];
        }
        $result = DB::table('task')
            ->where('is_delete',0)
            ->where('status',0)
            ->wherein('type',$type)
            ->where('create_time','<=',$time=='-1'?time():$time)
            ->orderby('create_time','desc')
            ->offset($start)
            ->limit($num)
            ->get();
        return $result;
    }
}
