<?php

namespace App\Models\Wx;
use DB;
use Illuminate\Database\Eloquent\Model;

class Lost extends Model
{

   public function get_list(){
        return DB::table('lost')->where('is_delete',0)->orderBy('create_time','desc')->get();

   }

}