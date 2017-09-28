<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Task extends Model
{

    public static function get_list($page_size = 50,$status = -1,$type = -1)
    {
        $sql = DB::table('task')->where('is_delete',0);
        if($status!=-1){
            $sql->where('status',$status);
        }
        if($type != -1){
            $sql->where('type',$type);
        }
        return $sql->paginate($page_size);
    }

    public static function task_delete($id)
    {

        DB::table('task')->where('id', $id)->update([
            'is_delete' => 1,
            'delete_at' => time()
        ]);

        return true;
    }

}
