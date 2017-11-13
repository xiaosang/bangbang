<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Notice extends Model
{
    public static function get($id)
    {
        $sql = DB::table('announcement')->where('is_delete',0)->where('id',$id);
        return $sql->select('id','content')->first();
    }

    public static function edit($notice)
    {
        if($notice['id']!=0)
            return DB::table('announcement')->where('is_delete',0)->where('id',$notice['id'])->update([
                'content'=>$notice['content']
            ]);
        else
            return DB::table('announcement')->insert([
                'content'=>$notice['content'],
                'create_time'=>time(),
                'create_user_id'=>get_session_user_id()
            ]);
    }

    public static function get_list($page_size = 50,$keyword = '')
    {
        $sql = DB::table('announcement')->where('is_delete',0);
        if($keyword!=''){
            $sql->where('content','like','%' . $keyword .'%');
        }
        return $sql->orderBy('create_time','desc')->paginate($page_size);
    }

    public static function notice_delete($id)
    {

        DB::table('announcement')->where('id', $id)->update([
            'is_delete' => 1
        ]);

        return true;
    }
}
