<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Excel;

class UserController extends Controller
{

     function get_list(Request $request)
    {
        $page_size = $request->page_size;
        return responseToJson(0, 'success', User::get_list($page_size));
    }
}
