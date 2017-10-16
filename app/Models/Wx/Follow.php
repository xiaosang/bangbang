<?php

namespace App\Models\Wx;
use DB;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{

    public static function get_school(){
        return DB::table('school')->where('is_delete','0')->get();
    }

}