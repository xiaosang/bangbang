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
    public static function get_list($page_size = 50)
    {
        $sql = DB::table('user');
        return $sql->paginate($page_size);
    }
}
