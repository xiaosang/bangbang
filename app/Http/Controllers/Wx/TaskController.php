<?php

namespace App\Http\Controllers\Wx;

use App\Models\Wx\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use App\Models\MonitorTask;

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
        $create_user_id = get_session_user_id();
        $key = str_rand(4);


        $task_id = Task::issue_task($name,$content,$type,$pay_money,$task_finish_time,$expected_time,$user_name,$user_phone,$address_name,$is_hide,$create_user_id,$key);
        $temp = $expected_time-time();//截至时间-当前时间
//        (new MonitorTask($task_id,$temp))->end();//todo  截止时间消失队列

        if($task_id){
            return responseToJson(1,'发布成功！', [$key , $task_id]);
        }else{
            return responseToJson(0,'发布失败！');
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
            $row->value =  date('Y-m-d H:i:s',$v->create_time);
            array_push($temp,json_decode(json_encode($row)));
            $row->label = "收货地址";
            $row->value =  $v->address_name;
            array_push($temp,json_decode(json_encode($row)));
            array_push($temp,$v->type);
            $btn_arr = [];
            $btn = (Object)[];
            $btn->style =  'default';
            $btn->text =  '接受任务';
            array_push($btn_arr,json_decode(json_encode($btn)));
            $btn->style =  'default';
            $btn->text =  '查看详情';
            $btn->link =  '/main/'.$v->id;
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
        if( $task_info && $task_info->is_delete == 0 && $task_info->status == 0 ){
            $task_info->credit_score = session('user')->credit_score;
            $task_info->wx_name = session('user')->name;
            $task_info->avatar = session('user')->avatar?session('user')->avatar:'/img/wx/wx_avatar.jpg';
            if($task_info->is_hide == 1){
                $task_info->user_name = '';
                $task_info->user_phone = '';
            }
            return responseToJson(1,'任务详情获取成功！',$task_info);
        }else{
            return responseToJson(0,'您老手慢了！');
        }
    }




}