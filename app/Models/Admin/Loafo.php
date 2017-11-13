<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Loafo extends Model
{
    public static function get_list($page_size = 50,$keyword = '')
    {
        $sql = DB::table('lost')->where('is_delete',0);
        if($keyword!=''){
            $sql->where('content','like','%' . $keyword .'%');
        }
        return $sql->orderBy('create_time','desc')->paginate($page_size);
    }

    public static function loafo_delete($id)
    {

        DB::table('lost')->where('id', $id)->update([
            'is_delete' => 1
        ]);

        return true;
    }
}
