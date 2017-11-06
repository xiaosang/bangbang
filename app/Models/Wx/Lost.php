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

   public static function get_info($id){
        return DB::table('lost')->where('id',$id)->first();
   }

}