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

}