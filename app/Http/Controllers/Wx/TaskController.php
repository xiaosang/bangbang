<?php

namespace App\Http\Controllers\Wx;

use App\Models\Wx\Task;
use App\Http\Controllers\Controller;
//use http\Env\Request;
//use Illuminate\Http\Request;
use Illuminate\Http\Request;

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





}