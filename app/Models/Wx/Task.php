<?php

namespace App\Models\Wx;
use Illuminate\Database\Eloquent\Model;
use DB;

class Task extends Model
{
    static public function index(){}

    /*发布任务
    $name,标题
    $content,内容
    $type,类型（0.有偿  1.无偿）
    $pay_money,支付金额（默认0）
    $task_finish_time,任务完成时间
    $expected_time,截止时间
    $is_hide是否匿名(0.不匿名1.匿名)
    */
    static public function add_task($name,$content,$type,$pay_money,$task_finish_time,$expected_time,$is_hide){
        $data = [];
        DB::table('task')
            ->insert($data);
    }
}
