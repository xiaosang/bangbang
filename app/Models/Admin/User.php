<?php

namespace App\Models\Admin;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Excel;

/**
 * 用户管理
 * Class User
 * @package App\Models
 */
class User extends Model
{
    //用户列表
    public static function get_list($page_size = 50,$status = -1,$input = '',$score = 0) {

        $sql = DB::table('user');
        if($status != -1)
            $sql->where('status',$status);
        if($input != '')
            $sql->where(function ($q) use ($input) {
                $q->where('name_quanpin', 'like', '%' . $input . '%')
                    ->orWhere('name_jianpin', 'like', '%' . $input . '%')
                    ->orWhere('code', 'like', '%' . $input . '%')
                    ->orWhere('name', 'like', '%' . $input . '%');
            });
        if($score == 1)
            $sql->where('credit_score', '>', 60);
        else if($score == 2)
            $sql->where('credit_score', '<', 60);
        $sql->orderBy('student_num');
        return $sql->paginate($page_size);
    }

    //修改用户状态
    public static function edit_status ( $id, $status ) {
        return DB::table('user')->where('id',$id)->update(['status' => $status]);
    }
}
