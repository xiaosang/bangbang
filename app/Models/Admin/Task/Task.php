<?php

namespace App\Models\Admin\Task;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Task extends Model
{

    public static function get_list($page_size = 50)
    {
        return DB::table('task')->where('is_delete',0)->paginate($page_size);
    }
}
