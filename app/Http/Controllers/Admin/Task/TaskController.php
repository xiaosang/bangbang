<?php

namespace App\Http\Controllers\Admin\Task;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Admin\Task\Task;

class TaskController extends Controller
{


    function get_list(Request $request)
    {
        // $res = ;
        // dd($res);
        $page_size = $request->page_size;
        return responseToJson(0, 'success', Task::get_list($page_size));
        // return $res;
    }



}
