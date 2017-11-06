<?php

namespace App\Http\Controllers\Wx;

use App\Models\Wx\Lost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class LostController extends Controller
{

    public function get_list(Request $request){
        $time = $request->time;
        $start = $request->start;
        $num = $request->num;
        $type = $request->type;
        $result = Lost::get_list($time,$start,$num,$type);
        $data = [];
        foreach ($result as $v){
            $temp = [];
            $row = (Object)[];
            $row->label = "标题";
            $row->value = $v->title;
            array_push($temp,json_decode(json_encode($row)));
            $row->label = "丢失时间";
            $row->value = date('Y-m-d H:i:s',$v->lost_time);
            array_push($temp,json_decode(json_encode($row)));
            $row->label = "发布时间";
            $row->value =  date('Y-m-d H:i:s',$v->create_time);//需要设置时区
            array_push($temp,json_decode(json_encode($row)));
            $row->label = "丢失地点";
            $row->value =  $v->place;
            array_push($temp,json_decode(json_encode($row)));
            array_push($temp,$v->is_lost);
            $btn_arr = [];
            $btn = (Object)[];
//            $btn->style =  'default';
//            $btn->text =  '接受任务';
//            array_push($btn_arr,json_decode(json_encode($btn)));
            $btn->style =  'default';
            $btn->text =  '查看详情';
            $btn->link =  '/main/lost/info/'.$v->id;
            array_push($btn_arr,json_decode(json_encode($btn)));
            array_push($temp,$btn_arr);
            array_push($data,$temp);
        }

        return responseToJson(0, 'success',$data);
        
    }

    public function lost_info(Request $request){
        $id = $request->id;
        // sendMsg(18737383137);
        return responseToJson(0, 'success',Lost::get_info($id));
    }

}