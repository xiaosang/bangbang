<?php

namespace App\Http\Controllers\Admin\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Admin\Index;

class IndexController extends Controller
{
    function get_task()
    {
        // return Response::json(Menu::get_format_menu());
        return responseToJson(0, 'success', Index::get_task_status());
        // dd();
    }
    function get_new_add_user(){
        // $time = time();
        // 86400
        $time = time(date('Y-m-d'));
        // dd(date('Y-m-d'),$time);
        $yet_time = $time - 86400;
        $res = Index::get_all_user();
        $res1 = Index::get_new_add_user($time,$yet_time);
        // dd($res,$res1);
        return responseToJson(0, 'success',['yet_follow'=>$res1[0]->count,'all_follow'=>$res[0]->count]);
        // dd($time);
    }

    function get_order(){
        return responseToJson(0, 'success',Index::get_order());
    }

}
