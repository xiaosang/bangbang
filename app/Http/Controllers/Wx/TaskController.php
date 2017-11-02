<?php

namespace App\Http\Controllers\Wx;

use App\Models\Wx\Task;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use DB;
use App\Models\MonitorTask;
use Exception;

class TaskController extends Controller
{

    public function index(){}

    public function get_user_info(){
        return responseToJson(1,'获得用户信息成功',get_wx_user());
    }

    public function get_address_list(){
        $user_id = get_wx_user()->id;
        $result = Task::get_address_list($user_id)->toarray();
        $data = [];
        $default = "";
        foreach($result as $key => $val){
            $data[$key]['name'] = $val->name;
            $data[$key]['value'] = $val->name;
            if($val->status == 1){
                $default = $val->name;
            }
        }
        return json_encode(['status'=>1,'msg'=>"获取收货地址列表成功",'result'=>$data,'default'=>$default]);
    }

    public function issue_task(Request $request){
//        Log::info($request->all());
        $name = trim($request->name);
        $content = trim($request->content);
        $type = (int)$request->type;
        $pay_money = $request->pay_money ? $request->pay_money*100 : 0 ;
        $task_finish_time = $request->task_finish_time;
        $expected_time = $request->expected_time;
        $user_name = trim($request->user_name);
        $user_phone = trim($request->user_phone);
        $address_name = trim($request->address_name);
        $is_hide = $request->is_hide ? 1 : 0 ;
        $create_user_id = get_wx_user()->id;
        $create_user_name = get_wx_user()->name;
        $key = str_rand(4);


        DB::beginTransaction();
        try{
            $task_id = Task::issue_task($name,$content,$type,$pay_money,$task_finish_time,$expected_time,$user_name,$user_phone,$address_name,$is_hide,$create_user_id,$create_user_name,$key);

            if($task_id){
                $temp = $expected_time-time();//截至时间-当前时间
//        (new MonitorTask($task_id,$temp))->end();//todo  截止时间消失队列
                $is_pay = $type?1:0;
                $status = 0;
                $order_code = time().'_'.uniqid();
                $insert_transaction_order = Task::insert_transaction_order($task_id,$type,$pay_money,$order_code,$is_pay,$status,$create_user_id,$create_user_name);
                if(!$insert_transaction_order){
                    throw new exception('发布失败');
                }
            }else{
                throw new exception('发布失败');
            }
            DB::commit();
            return responseToJson(1,'发布成功！',[$key , $task_id]);
        }catch(\Exception $e){
            DB::rollBack();
//            dd($e->getMessage());
            return responseToJson(0,$e->getMessage());

        }

    }

    /*
     * 创建任务时候生成订单和任务完成时生成订单
     * $type  入账  出账
     * $status 任务状态(0：未接受，1：已接受，2：已完成，3：已结束,4：已取消，5：到时间未接收)
     * $pay_money 支付金额
     * $task_id 任务ID
     *
     * */
    public function create_pay_order(Request $request){
        $pay_money = $request->pay_money*100;
        $task_id = $request->task_id;
        $user_id = get_wx_user()->id;
        $user_name = get_wx_user()->name;
        $type = 0;//0入账   1出账
        $status = 0;
        $order_code = time().'_'.uniqid();
        $create_pay_order = Task::create_pay_order($order_code,$type,$status,$user_id,$user_name,$pay_money,$task_id);
        if($create_pay_order){
            return responseToJson(1,'订单创建成功！');
        }else{
            return responseToJson(0,'订单创建失败！');
        }
    }
    /*
     *              [
                        {
                            label: '标题',
                            value: '买饭'
                        }, {
                            label: '时间',
                            value: '十分钟'
                        }, {
                            label: '发布时间',
                            value: '2017-10-01 17:43'
                        },
                        0,
                        [{
                            style: 'default',
                            text: '接受任务'
                        }, {
                            style: 'default',
                            text: '查看详情',
                            link: '/main/1'
                        }],
                    ]
    */
    public function get_task_list(Request $request){//首页列表
        $start = 0;
        $num = $request->num;
        $type = -1;
        $result = Task::get_task_list($start,$num,$type);
        $data = [];
        foreach ($result as $v){
            $temp = [];
            $row = (Object)[];
            $row->label = "标题";
            $row->value = $v->name;
            array_push($temp,json_decode(json_encode($row)));
            $row->label = "限定时间";
            $row->value = secsToStr($v->task_finish_time);
            array_push($temp,json_decode(json_encode($row)));
            $row->label = "发布时间";
            $row->value =  date('Y-m-d H:i:s',$v->create_time);//需要设置时区
            array_push($temp,json_decode(json_encode($row)));
            $row->label = "收货地址";
            $row->value =  $v->address_name;
            array_push($temp,json_decode(json_encode($row)));
            array_push($temp,$v->type);
            $btn_arr = [];
            $btn = (Object)[];
//            $btn->style =  'default';
//            $btn->text =  '接受任务';
//            array_push($btn_arr,json_decode(json_encode($btn)));
            $btn->style =  'default';
            $btn->text =  '查看详情';
            $btn->link =  '/main/task/info/'.$v->id;
            array_push($btn_arr,json_decode(json_encode($btn)));
            array_push($temp,$btn_arr);
            array_push($data,$temp);
        }
        return responseToJson(1,'获取数据成功！', $data);
    }

    public function get_task(Request $request){//任务列表
        $time = $request->time;
        $start = $request->start;
        $num = $request->num;
        $type = $request->type;
        $result = Task::get_task_list($start,$num,$type,$time);
        return responseToJson(1,"获取成功",$result);
    }

    public function get_task_info(Request $request){

        $id = $request->id;
        $task_info = Task::get_task_info($id);
        $create_user_info = User::get_info($task_info->create_user_id);
        $task_info->credit_score = $create_user_info->credit_score;
        $task_info->avatar = $create_user_info->avatar? $create_user_info->avatar:'/img/wx/wx_avatar.jpg';
        /*if( $request->type == 1 && $task_info->create_user_id != get_wx_user()->id){//编辑页面传过来的1
            return responseToJson(4,'不是自己发布的任务不能编辑');
        }else if($request->type == 1 && $task_info->create_user_id == get_wx_user()->id){
            return responseToJson(3,'我发布的任务',$task_info);
        }*/
        if($task_info){
            if($task_info->is_delete == 0){
                $transaction_order = Task::get_transaction_order($id);
                if($task_info->create_user_id == get_wx_user()->id){//我发布的任务
                    $task_info->is_hide = 0;//如果任务是我发布，对我不匿名
                    switch ($task_info->status){
                        case 0:
                            return responseToJson(1,'任务未接受',$task_info);
                        case 1:
                            return responseToJson(2,'任务已接受！',$task_info);
                        case 2:
                            return responseToJson(3,'任务已完成！',$task_info);
                        case 3:
                            return responseToJson(4,'任务已结束！',$task_info);
                        case 4:
                            return responseToJson(0,'任务不存在！',$task_info);
                    }
                }else if($transaction_order->accept_user_id!=0){//是否有人接受任务
                    if($transaction_order->accept_user_id == get_wx_user()->id){//我接受的任务
                        $task_info->is_hide = 0;//如果任务是我接受，对我不匿名
                        return responseToJson(6,'完成任务',$task_info);
                    }else{
                        return responseToJson(0,'任务不存在！');
                    }
                }else{
                    if($task_info->is_hide == 1){
                        $task_info->create_user_name = '匿名';
                        $task_info->user_name = '';
                        $task_info->user_phone = '';
                        $task_info->avatar = '/img/wx/wx_avatar.jpg';
                    }
                    return responseToJson(5,'任务没人接',$task_info);
                }
            }else if($task_info->is_delete == 1){
                return responseToJson(0,'任务不存在！');
            }
        }else{
            return responseToJson(0,'任务不存在！');
        }


        /*if( $task_info && $task_info->is_delete == 0 && $task_info->status != 4 ){
            $task_info->credit_score = $create_user_info->credit_score;
            $task_info->avatar = $create_user_info->avatar? $create_user_info->avatar:'/img/wx/wx_avatar.jpg';

            if($task_info->status == 0){
                if( $task_info->create_user_id == get_wx_user()->id ){
                    $task_info->is_hide = 0;
                    return responseToJson(3,'我发布的任务',$task_info);
                }else{
                    if($task_info->is_hide == 1){
                        $task_info->create_user_name = '匿名';
                        $task_info->user_name = '';
                        $task_info->user_phone = '';
                        $task_info->avatar = '/img/wx/wx_avatar.jpg';
                    }
                    return responseToJson(1,'任务详情获取成功！',$task_info);
                }
            }else if( $task_info->status != 4 ){
                $transaction_order = Task::get_transaction_order($id);
                if( $transaction_order->accept_user_id == get_wx_user()->id || $transaction_order->release_user_id == get_wx_user()->id){
                    $task_info->is_hide = 0;
                    return responseToJson(2,'我接受的任务',$task_info);
                }else{
                    return responseToJson(0,'您老手慢了！');
                }
            }

        }else{
            return responseToJson(0,'任务不存在！');
        }*/

        /*if( $task_info && $task_info->is_delete == 0 && $task_info->status == 0 ){
            $task_info->credit_score = get_wx_user()->credit_score;
            $task_info->avatar = get_wx_user()->avatar?get_wx_user()->avatar:'/img/wx/wx_avatar.jpg';
            if($task_info->is_hide == 1){
                $task_info->create_user_name = '匿名';
                $task_info->user_name = '';
                $task_info->user_phone = '';
            }
            return responseToJson(1,'任务详情获取成功！',$task_info);
        }else{
            return responseToJson(0,'您老手慢了！');
        }*/
    }

    public function accept_task(Request $request){
        DB::beginTransaction();
        try{
            $id = $request->id;
            $status = 1;
            $is_pay = 0;
            /*$task_info = Task::get_task_info($id);
            if($task_info->create_user_id == get_wx_user()->id){
                throw new exception('不允许接自己发布的任务');
            }*/
            $update_task = Task::accept_task($id,$status);
            if($update_task == 0){
                throw new exception('您下手慢了');
            }
//            $task_info = Task::get_task_info($id);
//            $insert_transaction_order = Task::insert_transaction_order($task_info->id,$task_info->type,$task_info->pay_money,'fdsfs45f64s56f45s64f6',$is_pay,$task_info->status,$task_info->create_user_id,$task_info->create_user_name,get_wx_user()->id,get_wx_user()->name);
            $update_transaction_order = Task::update_transaction_order($id,[
                'status'=>$status,
                'accept_user_id'=>get_wx_user()->id,
                'accept_user_name'=>get_wx_user()->name
            ]);
            if($update_transaction_order == 0){
                throw new exception('接受任务失败！');
            }

            DB::commit();
            return responseToJson(1,'接受任务成功！');
        }catch(\Exception $e){
            DB::rollBack();
//            dd($e->getMessage());
            return responseToJson(0,$e->getMessage());
        }
    }

    public function del_task(Request $request){
        $id = $request->id;
        $task_info = Task::get_task_info($id);
        if($task_info->status == 0){
            $result = Task::del_task($id);
            if($result){
                return responseToJson(1,'删除成功');
            }else{
                return responseToJson(0,'删除失败');
            }
        }else{
            return responseToJson(0,'该任务无法删除');
        }
    }

    public function get_announcement_list(){
        $result = Task::get_announcement_list()->toarray();
        return responseToJson(1,'获取公告！',$result);
    }

    public function get_announcementContent(){
        $result = Task::get_announcementContent();
        return responseToJson(1,'获取公告！',$result->content);
    }

    public function task_list(Request $request){
        $user_id = get_wx_user_id();
        $num = $request->num;
        $page = $request->page;
        $type = $request->type;
        $status = $request->status;
        $create_by_me = $request->create_by_me;
        $result = Task::task_list($user_id,$create_by_me,$type,$status,$num,$page);
        if($result){
            return responseToJson(1,'获取任务列表成功',$result);
        }else{
            return responseToJson(0,'获取任务列表失败');
        }
    }

}