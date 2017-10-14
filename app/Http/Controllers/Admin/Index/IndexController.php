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


}
