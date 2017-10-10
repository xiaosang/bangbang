<?php

namespace App\Http\Controllers\Admin\Task;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Admin\Task;

class TaskController extends Controller
{


    function get_list(Request $request)
    {
        // $res = ;
        // dd($res);
        $page_size = $request->page_size;
        return responseToJson(0, 'success', Task::get_list($page_size,$request->status,$request->type));
        // return $res;
    }

    function task_delete(Request $request){

        $id = $request->id;
        $res = Task::task_delete($id);
        if($res){
            return responseToJson(0,"删除成功");
        }else{
            return responseToJson(1, "删除失败");
        }

    }

    function get_over_list(Request $request){
        $page_size = $request->page_size;
        return responseToJson(0, 'success', Task::get_over_list($page_size,$request->status,$request->type));
    }

}
