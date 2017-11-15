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
    static public function issue_task($name,$content,$type,$pay_money=0,$task_finish_time,$expected_time,$user_name,$user_phone,$address_name,$is_hide,$create_user_id,$create_user_name,$key){

        $data = [
            'type'=>$type,
            'name'=>$name,
            'content'=>$content,
            'create_time'=>time(),
            'expected_time'=>$expected_time,
            'task_finish_time'=>$task_finish_time,
            'create_user_id'=>$create_user_id,
            'create_user_name'=>$create_user_name,
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
            ->leftJoin('transaction_order','task.id','=','transaction_order.task_id')
            ->where('transaction_order.is_pay',1)
            ->where('task.is_delete',0)
            ->where('task.status',0)
            ->wherein('task.type',$type)
            ->where('task.create_time','<=',$time=='-1'?time():$time)
            ->select('task.*')
            ->orderby('task.create_time','desc')
            ->offset($start)
            ->limit($num)
            ->get();
        return $result;
    }
    /*
     * 创建任务时候生成订单和任务完成时生成订单
     * $order_code 订单编号
     * $type  入账  出账
     * $status 任务状态(0：未接受，1：已接受，2：已完成，3：已结束,4：已取消，5：到时间未接收)
     * $pay_money 支付金额
     * $task_id 任务ID
     *
     * */
    static public function create_pay_order($order_code,$type,$status,$user_id,$user_name,$pay_money,$task_id){
        $data = [
            'order_code'=>$order_code,
            'type'=>$type,
            'create_time'=>time(),
            'status'=>$status,
            'user_id'=>$user_id,
            'user_name'=>$user_name,
            'pay_money'=>$pay_money,
            'task_id'=>$task_id
        ];
        $result = DB::table('pay_order')
            ->insert($data);
        return $result;
    }

    /*
     * 得到任务详情
     * $id 任务id
     * */
    static public function get_task_info($id){
        $result = DB::table('task')
            ->where('id',$id)
            ->first();
        return $result;
    }

    /*
     * 接受任务
     * $id
     * $status  任务状态(0：未接受，1：已接受，2：已完成，3：已结束,4：已取消)
     * */
    static public function accept_task($id,$status){
        $data = [
            'status'=>$status,
            'update_time'=>time()
        ];
        $result = DB::table('task')
            ->where('id',$id)
            ->where('status',0)
            ->update($data);
        return $result;
    }

    /*
     * 发布任务时，将任务添加到transaction_order表
     * $task_id,任务id
     * $type,任务类型
     * $pay_money,支付金额
     * $order_code,任务订单编号
     * $is_pay,是否支付
     * $status,任务状态
     * $release_user_id,发布任务者id
     * $release_user_name,发布任务者名字
     * $accept_user_id,接受任务者id
     * $accept_user_name接受任务者名字
     * */
    static public function insert_transaction_order($task_id,$type,$pay_money,$order_code,$is_pay,$status,$release_user_id,$release_user_name,$accept_user_id=0,$accept_user_name=''){
        $data = [
            'task_id' => $task_id,
            'type' => $type,
            'pay_money' => $pay_money,
            'order_code'=>$order_code,
            'is_pay' => $is_pay,
            'status'=>$status,
            'create_time'=>time(),
            'release_user_id' => $release_user_id,
            'release_user_name' => $release_user_name,
            'accept_user_id' => $accept_user_id,
            'accept_user_name' => $accept_user_name
        ];
        $result = DB::table('transaction_order')
            ->insert($data);
        return $result;
    }

    /*
     * 接受任务时，更新transaction_order
     * $data = [
                'status'=>$status,
                'accept_user_id'=>$accept_user_id,
                'accept_user_name'=>$accept_user_name
            ]
     * */
    static public function update_transaction_order($task_id,$data){
        $result = DB::table('transaction_order')
            ->where('task_id',$task_id)
            ->where('is_delete',0)
            ->update($data);
        return $result;
    }

    /*
     * 得到任务接受信息
     * $task_id
     * */
    static public function get_transaction_order($task_id){
        $result = DB::table('transaction_order')
            ->where('task_id',$task_id)
            ->where('is_delete',0)
            ->first();
        return $result;
    }

    /*
     * 支付成功调用，改变pay_order表和transaction_order状态
     *
     * */
    static public function pay_suc($task_id){
        DB::table('pay_order')
            ->where('task_id',$task_id)
            ->update(['is_pay'=>1]);
        DB::table('transaction_order')
            ->where('task_id',$task_id)
            ->update(['is_pay'=>1]);
    }

    /*
     * 获取公告列表
     * */
    static public function get_announcement_list(){
        $result = DB::table('announcement')
            ->where('is_delete',0)
            ->orderBy('create_time','desc')
            ->get();
        return $result;
    }

    /*
     * (首页)获取公告列表
     * */
    static public function get_announcementContent(){
        $result = DB::table('announcement')
            ->where('is_delete',0)
            ->orderBy('create_time','desc')
            ->first();
        return $result;
    }

    /**
     * 获取任务列表
     * $user_id 用户id
     * $create_by_me 1：我发布的  0:我接受的
     * $type  任务类型  0：有偿    1；无偿    -1：全部
     * $status  任务状态   -1:全部  0：未接受   1：已接受   2：已完成   3：已结束   4：已取消
     * $num 分页的每页的数量
     * $page 页号，从0开始
     */
    static public function task_list($user_id,$create_by_me,$type,$status,$num,$page)
    {
        if($create_by_me==0){
            $q = DB::table('task')->where(['task.is_delete'=>0])->leftJoin('transaction_order','transaction_order.task_id','=','task.id')->where('transaction_order.accept_user_id',$user_id);
        }else if($create_by_me==1){
            $q = DB::table('task')->where(['task.create_user_id'=>$user_id,'task.is_delete'=>0])->leftJoin('transaction_order','transaction_order.task_id','=','task.id');
        }
        if($type==-1){
            $q->where(function($q){
                $q->where('task.type',1)->orWhere('task.type',0);
            });
        }else{
            $q->where('task.type',$type);
        }
        if($status==-1){
            $q->where(function($q){
                $q->where('task.status',0)->orWhere('task.status',1)->orWhere('task.status',2)->orWhere('task.status',3)->orWhere('task.status',4);
            });
        }else{
            $q->where('task.status',$status);
        }
        $start = $num*$page;
        $result = $q->select('task.address_name','task.id','task.type','task.name','task.task_finish_time','transaction_order.pay_money')->offset($start)->limit($num)->get();
        return $result;
    }

    /*
     * 撤回任务
     * $task_id 任务id
     * */
    static public function del_task($task_id){
        $result = DB::table('task')
            ->where('status',0)
            ->where('id',$task_id)
            ->update(['status'=>4]);
        return $result;
    }

    /**
     * 完成任务操作
     */
    static function finish_task($task_id,$task_info){
        DB::beginTransaction();
        try {
            if($task_info->type==0){
                /**
                 * 支付接口后返回金钱
                 */

                $pay_money = 0;
                DB::table('pay_order')->insert(['create_time'=>time(),'delete_at'=>time(),'is_delete'=>0,'is_pay'=>1,'order_code'=>time().'_'.uniqid(),'pay_money'=>$pay_money,'status'=>2,'task_id'=>$task_id,'type'=>1,'user_id'=>get_wx_user_id(),'user_name'=>get_wx_user()->name]);
            }
            $credit_score_increment =env('CREDIT_SCORE_INCREMENT',2);
            DB::table('transaction_order')->where(['task_id'=>$task_id,'is_pay'=>1])->update(['status'=>2]);
            DB::table('task')->where(['id'=>$task_id,'is_delete'=>0])->update(['update_time'=>time(),'status'=>2]);
            DB::table('user')->where('id',get_wx_user_id())->increment('credit_score',$credit_score_increment);
            return 1;
        } catch (PDOException $ex) {
            DB::rollback();
            return 0;
        }
    }
}
