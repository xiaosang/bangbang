<?php

namespace App\Http\Controllers\Wx;

use App\Models\Wx\Task;
use App\Http\Controllers\Controller;
use http\Env\Request;

class TaskController extends Controller
{

    public function index(){}

    public function insert(Request $request){
        Task::taskInsert($request);
    }

}