<?php

namespace App\Http\Controllers\wx;

use App\Models\Wx\Proposal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;
use Response;
use Log;
use Redirect;

/**
 * 反馈与投诉
 * Class ProposalController
 * @package App\Http\Controllers\wx
 */
class ProposalController extends Controller
{
    /**
     * 用户反馈
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function submit_feedback(Request $request){
        $user_id = get_wx_user_id();
        $upload_file_num = $request->input('upload_file_num');
        for($i=0;$i<$upload_file_num;$i++){
            $upload_files[$i] = $request->file('upload_file'.$i);
        }
        $feedback_content = $request->input('feedback_content');
        if(mb_strlen($feedback_content)==0){
            return responseToJson(-2,'反馈内容不能为空');
        }
        $url = '';
        for ($i=0;$i<$upload_file_num;$i++){
            $file_new_name = getFilename($upload_files[$i]->getClientOriginalExtension());
            $upload_file_url = $upload_files[$i]->storeAs('feedback',$file_new_name);
            if($i==$upload_file_num-1){
                $url = $url.$upload_file_url;
            }else{
                $url = $url.$upload_file_url.';';
            }
        }
        $ip = $request->getClientIp();
        $user_agent = $request->userAgent();
        $result = Proposal::submit_feedback($user_id,$feedback_content,$url,$user_agent,$ip);
        if($result){
            return responseToJson(1,'反馈成功');
        }else{
            return responseToJson(0,'反馈失败');
        }
    }

    /**
     * 投诉列表
     * $judge_complaint 为0 我投诉的  ，   1 投诉我的
     */
    function get_complaint_list(Request $request){
        $user_id = get_wx_user_id();
        $judge_complaint = $request->input('judge_complaint');
        $page =$request->input('page');
        $num = $request->input('num');
        $list = Proposal::get_complaint_list($user_id,$judge_complaint,$page,$num);
        if($list){
            return responseToJson(1,'获取成功',$list);
        }
        return responseToJson(0,'获取失败');
    }

    function get_user_list(Request $request){
        $user_id = get_wx_user_id();
        $user_list = Proposal::get_user_list($user_id);
        if($user_list){
           return responseToJson(1,'获取成功',$user_list);
        }else{
            return responseToJson(0,'获取失败');
        }
    }

    function submit_complaint(Request $request){
        $user_id = get_wx_user_id();
        $upload_file_num = $request->input('upload_file_num');
        for($i=0;$i<$upload_file_num;$i++){
            $upload_files[$i] = $request->file('upload_file'.$i);
        }
        $complaint_content = $request->input('complaint_content');
        $complaint_user_id = $request->input('complaint_user_id');
        if(mb_strlen($complaint_content)==0){
            return responseToJson(-2,'投诉原因不能为空');
        }
        $url = '';
        for ($i=0;$i<$upload_file_num;$i++){
            $file_new_name = getFilename($upload_files[$i]->getClientOriginalExtension());
            $upload_file_url = $upload_files[$i]->storeAs('complaint',$file_new_name);
            if($i==$upload_file_num-1){
                $url = $url.$upload_file_url;
            }else{
                $url = $url.$upload_file_url.';';
            }
        }
        $ip = $request->getClientIp();
        $user_agent = $request->userAgent();
        $result = Proposal::submit_complaint($user_id,$complaint_content,$url,$user_agent,$ip,$complaint_user_id);
        if($result){
            return responseToJson(1,'反馈成功');
        }else{
            return responseToJson(0,'反馈失败');
        }
    }

    function single_complaint(Request $request){
        $user_id = get_wx_user_id();
        $complaint_id = $request->input('complaint_id');
        $result = Proposal::single_complaint($complaint_id,$user_id);
        if($result['complaint']&&$result['user']){
            return responseToJson(1,'获取成功',$result);
        }else{
            return responseToJson(0,'获取失败');
        }
    }

    function delete_complaint(Request $request){
        $user_id = get_wx_user_id();
        $complaint_id = $request->input('complaint_id');
        $result = Proposal::check_complaint($user_id,$complaint_id);
        if($result){
            $result = Proposal::delete_complaint($complaint_id);
            if($result){
                return responseToJson(1,'删除成功');
            }else{
                return responseToJson(0,'删除失败');
            }
        }else{
            return responseToJson(-1,'没有权限');
        }
    }

    function show_img(Request $request){
        $path = trim($request->path);
        return response()->download(storage_path('app/'.$path));
    }

}