<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Note extends Model{

    public static function get_list($page_size = 50){


        return DB::table('note')->where('is_delete',0)->paginate($page_size);

    }

    public static function get_comment($page_size = 50,$note_id){
        
        
        return DB::table('comment')->where('is_delete',0)->where('note_id',$note_id)->paginate($page_size);
        
    }

    public static function comment_delete($id)
    {
        DB::table('comment')->where('id', $id)->update([
            'is_delete' => 1,
            'comment_num' => 'comment_num' - 1,
            'delete_at' => time()
        ]);
        return true;
    }

    public static function note_delete($id)
    {
        DB::table('note')->where('id', $id)->update([
            'is_delete' => 1,
            'delete_at' => time()
        ]);
        return true;
    }
}