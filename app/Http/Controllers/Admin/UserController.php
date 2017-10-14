<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;

class UserController extends Controller
{

    function get_list(Request $request) {

        $page_size = $request->page_size;
        $status = $request->status;
        $input = $request->input;
        $score = $request->score;

        return responseToJson(0, 'success', User::get_list($page_size,$status,$input,$score));
    }

    function edit_status (Request $request) {

        $id = $request->id;
        $status = $request->status;

        if(User::edit_status($id,$status)){
            return  responseToJson(0, '修改成功');
        }
        return responseToJson(0, '修改失败');
    }
}
