<?php

namespace App\Models\Wx;
use DB;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{

    public static function get_school(){
        return DB::table('school')->where('is_delete','0')->get();
    }


    public static function save_info($info){
            DB::table('user')->where('openid', $info['openid'])->update([
                'name' => $info['name'],
                'sex' => $info['sex'],
                'code' => $info['student_code'],
                'student_class' => $info['student_class'],
                'department' => $info['student_department'],
                'student_num' => $info['student_num'],
                'is_v' => 1,
            ]);
            return DB::table('user')->where('openid', $info['openid'])->first();
    }


    public static function is_set($openid){
        return DB::table('user')->where('openid', $openid)->first();
    }
}