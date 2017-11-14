<?php

namespace App\Models\Wx;
use DB;
use Illuminate\Database\Eloquent\Model;

class Lost extends Model
{

   public static function get_list($time,$start,$num,$type){
       $sql = DB::table('lost')
       ->where('is_delete',0);
       if($type != 0){
        $sql = $sql->where('is_lost',$type);
       }
       return $sql->where('create_time','<',$time)
       ->orderBy('create_time','desc')
       ->offset($start)
       ->limit($num)
       ->get();
   }

   public static function submit_lost($title,$user_name="",$user_id,$content,$lost_time,$place,$create_time,$status,$is_lost,$is_delete,$reward_content="",$phone_num,$img_path=""){
       $data = [
           'title'=>$title,
           'user_name'=>$user_name,
           'user_id'=>$user_id,
           'content'=>$content,
           'lost_time'=>$lost_time,
           'place'=>$place,
           'create_time'=>$create_time,
           'status'=>$status,
           'is_lost'=>$is_lost,
           'is_delete'=>$is_delete,
           'reward_content'=>$reward_content,
           'phone_num'=>$phone_num,
           'img_path'=>$img_path
       ];
       $result = DB::table('lost')
           ->insert($data);
       return $result;
   }

   public static function get_info($id){
        return DB::table('lost')->where('id',$id)->where('is_delete',0)->first();
   }

   public static function finish_lost($id){
        DB::table('lost')->where('id',$id)->where('is_delete',0)->update(['status'=>1]);
        return true;
   }

    public static function delete_lost($id){
        DB::table('lost')->where('id',$id)->update(['is_delete'=>1]);
        return true;
    }   
}