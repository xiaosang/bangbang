<?php

namespace App\Models\Wx;
use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Proposal extends Model{
    /**
     * 提交反馈信息
     * @param $user_id
     * @param $message
     * @param $path  文件路径 相对于app 以 ; 分割
     * @param $useragent  代理
     * @param $ip   ip地址
     * @return bool
     */
    static function submit_feedback($user_id,$message,$path,$useragent,$ip){
        $result = DB::table('feedback')->insert(['message'=>$message,'path'=>$path,'useragent'=>$useragent,'ip'=>$ip,'created_at'=>time(),'create_user_id'=>$user_id]);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取投诉列表
     * @param $user_id
     * @param $judge_complaint  0 我投诉的 ，1 投诉我的
     * @param $page
     * @param $num
     */
    static function get_complaint_list($user_id,$judge_complaint,$page,$num){
        $start = $page*$num;
        $q = DB::table('complaint')->where('complaint.is_delete',0)->offset($start)->limit($num)->leftJoin('user','user.id','=','complaint.complaint_user_id');
        if($judge_complaint==0){
            $q->where('complaint.create_user_id',$user_id);
        }else if($judge_complaint==1){
            $q->where('complaint.complaint_user_id',$user_id);
        }
        $result = $q->select('complaint.id','complaint.content','complaint.create_time','user.name')->orderBy('complaint.create_time','desc')->get();
        if($result){
            return $result;
        }else{
            return 0;
        }
    }

    static function get_user_list($user_id){
        $user_list = DB::table('user')->where('id','!=',$user_id)->where(['status'=>0,'is_v'=>1])->get();
        return $user_list;
    }

    static function submit_complaint($create_user_id,$content,$path,$useragent,$ip,$complaint_user_id){
        $result = DB::table('complaint')->insert(['content'=>$content,'path'=>$path,'useragent'=>$useragent,'ip'=>$ip,'create_time'=>time(),'create_user_id'=>$create_user_id,
            'complaint_user_id'=>$complaint_user_id,'update_time'=>0]);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取单个投诉信息及相关用户
     * 其中 judge_complaint=0时user_id投诉user_else_id  session里面的用户投诉别人 judge_complaint=1时相反
     * @param $complaint_id
     * @param $user_id
     * @return mixed
     */
    static function single_complaint($complaint_id,$user_id){
        $result['complaint'] = DB::table('complaint')->where('id',$complaint_id)->where('is_delete',0)->where(function ($q) use($user_id){
            $q->where('create_user_id',$user_id)->orWhere('complaint_user_id',$user_id);
        })->first();
        if($result['complaint']){
            $user_id1 = $result['complaint']->create_user_id;
            $user_id2 = $result['complaint']->complaint_user_id;
            if($user_id1==$user_id){
                $user_id_else = $user_id2;
                $result['judge_complaint'] = 0;
            }else{
                $user_id_else = $user_id1;
                $result['judge_complaint'] = 1;
            }
            $result['user'] = DB::table('user')->where('id',$user_id_else)->first();
        }
        return $result;
    }

    /**
     * 检测学生的
     * @param $user_id
     * @param $complaint_id
     */
    static function check_complaint($user_id,$complaint_id){
        $result = DB::table('complaint')->where('id',$complaint_id)->where('create_user_id',$user_id)->first();
        if(count($result)==0){
            return false;
        }
        return true;
    }

    /**
     * 删除投诉
     * @param $user_id
     * @param $complaint_id
     */
    static function delete_complaint($complaint_id){
        $result = DB::table('complaint')->where(['id'=>$complaint_id,'is_delete'=>0])->update(['is_delete'=>1]);
        if($result){
            return $result;
        }
        return 0;
    }

}