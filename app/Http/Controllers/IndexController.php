<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Log;
use DB;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        //依据角色判断跳转到哪里
        $user = get_session_user();
        if ($user) {
            return view('admin.index');
        }
        return view("login");

    }

}