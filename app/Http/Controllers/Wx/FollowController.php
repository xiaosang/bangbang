<?php

namespace App\Http\Controllers\Wx;

use App\Models\wx\Follow;
use App\Http\Controllers\Controller;
class FollowController extends Controller
{

    public function get_school()
    {
        $res = Follow::get_school();
        $data = [];
        // dd($res);
        foreach ($res as $key => $value) {
            
            $data[$key]['name'] = $value->name;
            $data[$key]['value'] = $value->id;
        }

        // dd($data);
        return responseToJson(0,'success',$data);
    }

}