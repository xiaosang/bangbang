<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Index extends Model
{

    public static function get_task_status(){
        return DB::table('task')->where('is_delete','=',0)->groupBy('status')->selectRaw('status,count(id) as count')->get();
    }
}
