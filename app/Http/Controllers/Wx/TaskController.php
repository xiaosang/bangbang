<?php

namespace App\Http\Controllers\Wx;

use App\Models\Wx\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

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

        $result = Task::issue_task($name,$content,$type,$pay_money,$task_finish_time,$expected_time,$user_name,$user_phone,$address_name,$is_hide,$create_user_id,$key);

        if($result){
            return responseToJson(1,'发布成功！', $key);
        }else{
            return responseToJson(0,'发布失败！');
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
    public function get_task_list(){
        $result = Task::get_task_list(5);
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
//        dd($data);
        return $data;
    }




}