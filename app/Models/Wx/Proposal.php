<?php

namespace App\Models\Wx;
use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Proposal extends Model{
    static function submit_feedback($user_id,$message,$path,$useragent,$ip){
        $result = DB::table('feedback')->insert(['message'=>$message,'path'=>$path,'useragent'=>$useragent,'ip'=>$ip,'created_at'=>time(),'create_user_id'=>$user_id]);
        if($result){
            return true;
        }else{
            return false;
        }
    }
}