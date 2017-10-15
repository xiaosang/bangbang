<?php

namespace App\Http\Controllers;

use App\Models\MonitorTask;
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
    //测试专用
    public function test () {
        (new MonitorTask(35,10))->end();
        (new MonitorTask(36,30))->end();
        echo '12341';
    }
}