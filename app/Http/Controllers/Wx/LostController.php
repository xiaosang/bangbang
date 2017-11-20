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
        $user_id = 0;
        if($request->flag != 0){
            $user_id = get_session_user_id();
        }
        $result = Lost::get_list($time,$start,$num,$type,$user_id);
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
            $row->label = "是否完成";
            $row->value =  $v->status == 0 ? '未完成' : '已完成';
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


    /*public function upload_img(Request $request){
        dd($request->all());
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $file = $request->photo;
            return $file;
        }
    }*/

//    public function upload_img(Request $request){
//        $rlt = $this->upload_data($request);
//        if ($rlt["success"] == false) {
//            return json_encode($rlt);
//        } else {
//            return json_encode($rlt['message']);
//        }
//    }
//
//    public function upload_data(Request $request)
//    {
////        dd($request->img);
////        dd($request->hasFile('img'));
//        if (!$request->hasFile('img')) {
//            return [
//                'success' => false,
//                'message' => '上传文件为空'
//            ];
//        }
//        $file = $request->file('img');
//        if (!$file->isValid()) {
//            return [
//                'success' => false,
//                'message' => '文件上传出错'
//            ];
//        }
//        $extension = $file->getClientOriginalExtension();
//        $storage_path_max = storage_path('/app/lose/max/');
//        $storage_path_min = storage_path('/app/lose/min/');
//        if (!file_exists($storage_path_max)||!file_exists($storage_path_min)) {
//            mkdir($storage_path_max, 0777, true);
//            mkdir($storage_path_min, 0777, true);
//        }
//        $filename = getFilename("jpg");
////        $min_save = thumbnail($file,$filename,$storage_path_min,58,58);
//
///*        **
//        * 生成缩略图函数（支持图片格式：gif、jpeg、png）
//    * @author ruxing.li
//    * @param  string $src      源图片路径
//    * @param  string $filename 保存名字
//    * @param  string $filename 保存路径
//    * @param  int    $width    缩略图宽度（只指定高度时进行等比缩放）
//    * @param  int    $height    缩略图高度（只指定宽度时进行等比缩放）
//    * @return bool
//
//function thumbnail($src, $filename, $filepath, $width = 150, $height = null) {
//
//}*/
//        if ($file->move($storage_path_max, $filename) == false && $min_save == false ) {
//            return [
//                'success' => false,
//                'message' => '文件保存失败'
//            ];
//        }
//        return [
//            'success' => true,
//            'message' => $filename
//        ];
//    }

    /**
     * 发布丢失拾到
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function submit_lost(Request $request)
    {
        $yzm = check_msg($request->yzm,$request->user_phone);
        if(!$yzm){
            return responseToJson(0, '验证码有误');
        }
        $user_id = get_wx_user_id();
        $upload_file_num = $request->input('upload_file_num');
        for ($i = 0; $i < $upload_file_num; $i++) {
            $upload_files[$i] = $request->file('upload_file' . $i);
        }
        $name = $request->input('name');
        $content = $request->input('content');
        $type = $request->input('type');
        $reward_content = $request->input('reward_content');
        $user_name = $request->input('user_name');
        $user_phone = $request->input('user_phone');
        $lost_time = strtotime($request->input('complete_time'));
        $place = $request->input('place');

        $url = '';
        for ($i = 0; $i < $upload_file_num; $i++) {
            $file_new_name = getFilename($upload_files[$i]->getClientOriginalExtension());
            $upload_file_url = $upload_files[$i]->storeAs('lost/min', $file_new_name);

            if ($i == $upload_file_num - 1) {
                $url = $url . $file_new_name;
            } else {
                $url = $url . $file_new_name . ';';
            }
        }
        $result = Lost::submit_lost($name, $user_name, $user_id, $content, $lost_time, $place, time(), 0, $type, 0, $reward_content, $user_phone, $url);
//        $result = Proposal::submit_feedback($user_id,$feedback_content,$url,$user_agent,$ip);
        if ($result) {
            return responseToJson(1, '成功');
        } else {
            return responseToJson(0, '失败');
        }
    }
    public function lost_info(Request $request){
        $id = $request->id;
        $res = Lost::get_info($id);
        // dd();
        return responseToJson(0, 'success',['res'=>$res,'is_self'=>get_session_user_id()==$res->user_id]);
    }


    public function send_yzm(Request $request){
        $result = sendMsg($request->phone);
        if($result == 0){
            return responseToJson(0,'发送成功');
        }else if($result == 1){
            return responseToJson(1,'发送失败，服务器繁忙');
        }else if($result == 2){
            return responseToJson(2,'已经发过，无需再次请求');
        }
    }

    public function finish_lost(Request $request){
        Lost::finish_lost($request->id);
        return responseToJson(0,'success');

    }

    public function delete_lost(Request $request){
        Lost::delete_lost($request->id);
        return responseToJson(0,'success');
                
    }
    

}
